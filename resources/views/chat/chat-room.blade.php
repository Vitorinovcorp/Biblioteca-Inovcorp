@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 h-[calc(100vh-150px)]">
    @livewire('chat.chat-room', ['salaId' => $sala->id])
</div>
@endsection