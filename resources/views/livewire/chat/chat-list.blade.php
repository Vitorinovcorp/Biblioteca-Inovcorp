<div class="bg-white rounded-lg shadow h-full">
    <div class="p-4 border-b">
        <h2 class="text-xl font-bold mb-4">Conversas</h2>
        
        <div class="mb-4">
            <input type="text"
       wire:model.defer="novaMensagem"
       wire:keydown.enter.prevent="enviarMensagem"
       class="flex-1 px-4 py-2 border rounded-lg">
        </div>
    </div>

    <div class="overflow-y-auto h-[calc(100%-120px)]">
        @if($salas->count() > 0)
            <div class="mb-4">
                <h3 class="px-4 text-sm font-semibold text-gray-500 uppercase mb-2">Salas</h3>
                @foreach($salas as $sala)
                    <a href="{{ route('chat.conversa', $sala->id) }}" 
                       class="flex items-center px-4 py-3 hover:bg-gray-50 transition">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                            {{ substr($sala->nome, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold">{{ $sala->nome }}</p>
                            <p class="text-sm text-gray-500 truncate">
                                {{ $sala->mensagens->last()->conteudo ?? 'Clique para conversar' }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if($usuarios->count() > 0)
            <div>
                <h3 class="px-4 text-sm font-semibold text-gray-500 uppercase mb-2">Utilizadores</h3>
                @foreach($usuarios as $usuario)
                    <button wire:click="criarConversa({{ $usuario->id }})"
                            class="w-full flex items-center px-4 py-3 hover:bg-gray-50 transition text-left">
                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold mr-3">
                            {{ substr($usuario->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold">{{ $usuario->name }}</p>
                            <p class="text-sm text-gray-500">
                                Clique para conversar
                            </p>
                        </div>
                    </button>
                @endforeach
            </div>
        @endif

        @if($salas->count() == 0 && $usuarios->count() == 0)
            <div class="text-center text-gray-500 py-8">
                <p>Nenhuma conversa encontrada</p>
            </div>
        @endif
    </div>
</div>