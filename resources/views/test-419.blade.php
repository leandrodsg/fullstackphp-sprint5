@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Teste de CSRF - 419 Error</h1>
        
        <div class="mb-6">
            <p class="text-gray-600 mb-4">Esta página testa se o erro 419 (Page Expired) foi resolvido.</p>
            <p class="text-sm text-gray-500">CSS carregado: <span id="css-status" class="font-semibold"></span></p>
        </div>

        <!-- Formulário de teste para CSRF -->
        <form method="POST" action="{{ route('test.csrf') }}" class="space-y-4">
            @csrf
            <div>
                <label for="test_input" class="block text-sm font-medium text-gray-700 mb-2">
                    Campo de Teste:
                </label>
                <input 
                    type="text" 
                    id="test_input" 
                    name="test_input" 
                    value="Teste CSRF"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition-colors"
            >
                Testar Envio (CSRF)
            </button>
        </form>

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<script>
    // Verificar se o CSS foi carregado
    document.addEventListener('DOMContentLoaded', function() {
        const testElement = document.createElement('div');
        testElement.className = 'bg-blue-500';
        document.body.appendChild(testElement);
        
        const computedStyle = window.getComputedStyle(testElement);
        const backgroundColor = computedStyle.backgroundColor;
        
        document.body.removeChild(testElement);
        
        const statusElement = document.getElementById('css-status');
        if (backgroundColor === 'rgb(59, 130, 246)' || backgroundColor.includes('59, 130, 246')) {
            statusElement.textContent = '✅ CSS Tailwind carregado corretamente';
            statusElement.className += ' text-green-600';
        } else {
            statusElement.textContent = '❌ CSS Tailwind NÃO carregado';
            statusElement.className += ' text-red-600';
        }
    });
</script>
@endsection