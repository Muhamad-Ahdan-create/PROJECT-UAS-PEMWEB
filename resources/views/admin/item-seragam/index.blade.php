@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Item Seragam</h5>
        <a href="{{ route('admin.item-seragam.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Item
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Nama Item</th>
                            <th class="text-center">Ukuran</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->nama_item }}</td>
                                <td class="text-center">{{ $item->ukuran ?? '-' }}</td>
                                <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $item->stok }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.item-seragam.edit', $item) }}"
                                        class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.item-seragam.destroy', $item) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Hapus item ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada item seragam.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $items->links() }}</div>
    </div>
@endsection