<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ItemEdit extends Component
{
    use WithFileUploads;

    public Item $item;

    #[Validate('required|string|max:255')]
    public $code = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|image|max:2048')]
    public $image;

    #[Validate('required|numeric|min:0')]
    public $price = '';

    public $currentImage = '';

    public function __construct()
    {
        $this->authorize('edit-items');
    }

    public function mount(Item $item)
    {
        $this->item = $item;
        $this->code = $item->code;
        $this->name = $item->name;
        $this->price = $item->price;
        $this->currentImage = $item->image;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:items,code,' . $this->item->id,
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function update()
    {
        $this->validate();

        try {
            $imagePath = $this->currentImage;

            if ($this->image) {
                // Delete old image if exists
                if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
                    Storage::disk('public')->delete($this->currentImage);
                }

                $imageName = time() . '_' . $this->image->getClientOriginalName();
                $imagePath = $this->image->storeAs('items', $imageName, 'public');

                // Resize image using Intervention Image
                $manager = new ImageManager(new Driver());
                $image = $manager->read(storage_path('app/public/' . $imagePath));
                $image->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save(null, 85);
            }

            $this->item->update([
                'code' => $this->code,
                'name' => $this->name,
                'image' => $imagePath,
                'price' => $this->price,
            ]);

            session()->flash('message', 'Item berhasil diperbarui!');
            return redirect()->route('items.index');

        } catch (\Exception $e) {
            // Cleanup uploaded file if there was an error
            if (isset($imagePath) && $imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            session()->flash('error', 'Gagal memperbarui item. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function render()
    {
        return view('livewire.items.item-edit')
            ->layout('layouts.app', ['title' => 'Edit Item']);
    }
}
