@extends('layouts.app')


@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('admin.bots.index.partials.create-and-connect-bot-form')
            @include('admin.bots.index.partials.all-bots')
            
        </div>
    </div>
@endsection

@section('script')
    <script>
        const select = document.getElementById('messanger-select');
        const input = document.getElementById('url');

        select.addEventListener('change', (event) => {
            input.value = event.target.value;
        });
    </script>
@endsection
