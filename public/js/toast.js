function atualizarContadorCarrinho(total) {
    var contador = document.getElementById('carrinho-contador');
    if (contador) {
        if (total > 0) {
            contador.textContent = total;
            contador.classList.remove('hidden');
        } else {
            contador.classList.add('hidden');
        }
    }
}

function mostrarToast(mensagem, tipo) {
    var toast = document.getElementById('toast-message');
    if (!toast) return;
    
    var toastText = document.getElementById('toast-text');
    var toastDiv = toast.querySelector('div');
    
    toastDiv.classList.remove('toast-show', 'toast-hide');
    
    if (tipo === 'success') {
        toastDiv.className = 'bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3 toast-show';
        toastText.innerHTML = '<i class="fas fa-check-circle mr-2"></i> ' + mensagem;
    } else if (tipo === 'error') {
        toastDiv.className = 'bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3 toast-show';
        toastText.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> ' + mensagem;
    }
    
    toast.classList.remove('hidden');
    
    setTimeout(function() {
        toastDiv.classList.remove('toast-show');
        toastDiv.classList.add('toast-hide');
        setTimeout(function() {
            toast.classList.add('hidden');
        }, 300);
    }, 3000);
}