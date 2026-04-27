<div class="h-full flex flex-col bg-white">

    <!-- Cabeçalho -->
    <div class="p-4 border-b flex items-center justify-between bg-white">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                {{ strtoupper(substr($sala->nome, 0, 1)) }}
            </div>

            <div>
                <h2 class="font-bold text-gray-800">{{ $sala->nome }}</h2>
            </div>
        </div>

        <a href="{{ route('chat.index') }}"
           class="text-gray-500 hover:text-red-500 transition">
            ✕
        </a>
    </div>

    <!-- Mensagens -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-white"
         id="mensagens-container">

        @forelse($mensagens as $mensagem)
            <div wire:key="msg-{{ $mensagem->id }}"
                 class="flex {{ $mensagem->user_id == Auth::id() ? 'justify-end' : 'justify-start' }}">

                <div class="max-w-xs lg:max-w-md">

                    <div class="flex items-end space-x-2">

                        @if($mensagem->user_id != Auth::id())
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr($mensagem->user->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="{{ $mensagem->user_id == Auth::id()
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-800' }}
                            rounded-2xl px-4 py-2 shadow-sm">

                            @if($mensagem->user_id != Auth::id())
                                <p class="text-xs font-semibold mb-1 text-gray-600">
                                    {{ $mensagem->user->name }}
                                </p>
                            @endif

                            <p>{{ $mensagem->conteudo }}</p>

                            <p class="text-[11px] mt-1 text-right
                                {{ $mensagem->user_id == Auth::id()
                                    ? 'text-blue-100'
                                    : 'text-gray-400' }}">
                                {{ $mensagem->created_at->format('H:i') }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-400 py-10">
                Nenhuma mensagem ainda.
            </div>
        @endforelse

    </div>

    <!-- Input -->
    <div class="p-4 border-t bg-white">
        <div class="flex items-center space-x-2">

            <input
                id="chatInput"
                type="text"
                wire:model="novaMensagem"
                wire:keydown.enter.prevent="enviarMensagem"
                autocomplete="off"
                placeholder="Digite sua mensagem..."
                class="flex-1 px-4 py-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button wire:click="enviarMensagem"
                    class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition">
                ➤
            </button>

        </div>
    </div>

</div>
