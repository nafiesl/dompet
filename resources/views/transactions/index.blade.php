@extends('layouts.app')

@section('title', __('transaction.list'))

@section('content')
<div class="w-full md:w-3/6 my-16 mx-auto">
    <h1 class="text-3xl text-center">
        {{ __('transaction.list') }}
    </h1>

    @forelse ($transactions as $transaction)
    <div class="border-l-4 {{ $transaction->in_out == '1' ? 'border-green-600' : 'border-red-600' }} bg-white rounded md:w-4/6 mx-auto my-4 p-4">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="break-words font-bold">{{ $transaction->description }}</h2>
                @php
                    $transaction_date = \Carbon\Carbon::parse($transaction->date, 'Asia/Makassar')->locale('id');
                @endphp
                <p class="mb-4 break-words">Rp{{ number_format($transaction->amount) }} • {{ $transaction_date->isoFormat('LL') }}</p>
                @if ($transaction->category)
                    @php
                        $categoryRoute = route('categories.show', [
                            $transaction->category_id,
                            'start_date' => $startDate,
                            'end_date' => $year.'-'.$month.'-'.date('t'),
                        ]);
                    @endphp
                    <a href="{{ $categoryRoute }}" class="rounded border px-2 text-white bg-black border-black">{{ $transaction->category->name }}</a>
                @endif
            </div>
            <div>
                <p class="uppercase border px-2 rounded text-white
                    {{ $transaction->in_out == '1' ? 'bg-green-600 border-green-600' : 'bg-red-600 border-red-600' }}">
                    {{ $transaction->in_out == '1' ? 'masuk' : 'keluar' }}
                </p>
            </div>
        </div>
    </div>
    @empty
        <p class="font-bold">Belum ada transaksi. Silakan diisi ya. :)</p>
    @endforelse
</div>
@endsection