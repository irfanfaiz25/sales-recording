<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ItemCreate extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255|unique:items,code')]
    public $code = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|image|max:2048')]
    public $image;

    #[Validate('required|numeric|min:0')]
    public $price = '';


    public function __construct()
    {
        $this->authorize('create-items');
    }

    public function mount()
    {
        $this->generateCode();
    }

    public function generateCode()
    {
        do {
            $this->code = 'ITM-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Item::where('code', $this->code)->exists());
    }

    public function save()
    {
        $this->validate();

        try {
            $imagePath = null;

            if ($this->image) {
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

            Item::create([
                'code' => $this->code,
                'name' => $this->name,
                'image' => $imagePath,
                'price' => $this->price,
            ]);

            session()->flash('message', 'Item berhasil ditambahkan!');
            return redirect()->route('items.index');
        } catch (\Exception $e) {
            // Cleanup uploaded file if there was an error
            if (isset($imagePath) && $imagePath && \Storage::disk('public')->exists($imagePath)) {
                \Storage::disk('public')->delete($imagePath);
            }

            session()->flash('error', 'Gagal menambahkan item. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function render()
    {
        return view('livewire.items.item-create')
            ->layout('layouts.app', ['title' => 'Tambah Item']);
    }
}
