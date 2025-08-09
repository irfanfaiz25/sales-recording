<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;

use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
#[Title('Daftar Item')]
class ItemIndex extends Component
{
    public function __construct()
    {
        $this->authorize('manage-items');
    }

    public function delete($id)
    {
        $this->authorize('delete-items');

        $item = Item::find($id);
        if ($item) {
            try {
                // Check if item is used in any sales
                if ($item->saleItems()->count() > 0) {
                    session()->flash('error', 'Item tidak dapat dihapus karena sudah digunakan dalam penjualan.');
                    return;
                }

                // Delete image file if exists
                if ($item->image && file_exists(storage_path('app/public/' . $item->image))) {
                    unlink(storage_path('app/public/' . $item->image));
                }

                $item->delete();
                session()->flash('success', 'Item berhasil dihapus.');
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal menghapus item: ' . $e->getMessage());
                return redirect()->back();
            }

            $item->delete();
            session()->flash('success', 'Item berhasil dihapus.');
        }
    }

    public function render()
    {
        $items = Item::latest()->get();

        return view('livewire.items.item-index', compact('items'));
    }
}
