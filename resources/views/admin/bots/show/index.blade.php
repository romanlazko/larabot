@extends('layouts.app')

@section('content')

    <div class="sticky top-0 z-10">
        @include('admin.bots.show.partials.header')
    </div>
    <div class="grid sm:grid-cols-2">
        <div class="flex flex-col ">
            @include('admin.bots.show.partials.chats')
        </div>

        <div class="flex flex-col">
            @include('admin.bots.show.partials.errors')
        </div>
    </div>
    
    
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.collapseButton').click(function() {
                $(this).closest('.collapse-content').find('.content').toggle();
            });
        });
    </script>
@endsection

