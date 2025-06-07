@extends('layouts.app')

@section('content')
<style>
    .form-container {
        max-width: 700px;
        margin: 3rem auto;
        background: white;
        padding: 2rem 2.5rem;
        border-radius: 1rem;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    .form-title {
        font-weight: bold;
        font-size: 1.6rem;
        color: #2c3e50;
    }

    .stepper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .stepper span {
        margin: 0 10px;
        font-weight: 600;
        color: #95a5a6;
        position: relative;
    }

    .stepper span.active {
        color: #3e63f0;
        border-bottom: 2px solid #3e63f0;
        padding-bottom: 3px;
    }

    .stepper span.completed {
        color: #27ae60;
    }

    .payment-icons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        align-items: center;
        margin: 1rem 0 1.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
    }

    .payment-icons img {
        height: 35px;
        transition: transform 0.2s;
    }

    .payment-icons img:hover {
        transform: scale(1.1);
    }

    .btn-pagar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 999px;
        border: none;
        width: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-pagar:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-pagar:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #3e63f0;
        box-shadow: 0 0 0 0.2rem rgba(62, 99, 240, 0.25);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .form-control.is-valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .invalid-feedback {
        display: block;
        font-size: 0.875rem;
        color: #dc3545;
        margin-top: 0.25rem;
    }

    .input-icon {
        position: relative;
    }

    .input-icon i {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .card-preview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 1rem;
        margin: 1rem 0;
        font-family: 'Courier New', monospace;
    }

    .card-number {
        font-size: 1.2rem;
        letter-spacing: 2px;
        margin: 1rem 0;
    }

    .card-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .loading-spinner {
        display: none;
        margin-right: 0.5rem;
    }

    .security-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #e8f5e8;
        color: #155724;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        margin: 1rem 0;
    }
</style>

<div class="form-container">
    <div class="stepper">
        <span class="completed">✓ Recibo</span>
        <span class="active">💳 Pago</span>
        <span>🧾 Transacción</span>
    </div>

    <h3 class="form-title mb-3">💳 Pago en línea</h3>
    <p class="text-muted">Paga tu factura de manera segura y rápida</p>

    <div class="security-badge">
        <i class="bi bi-shield-check"></i>
        <span>Conexión segura SSL - Tus datos están protegidos</span>
    </div>

    <!-- Vista previa de la tarjeta -->
    <div class="card-preview">
        <div>ADACECAM</div>
        <div class="card-number" id="cardDisplay">•••• •••• •••• ••••</div>
        <div class="card-info">
            <span id="cardHolder">NOMBRE DEL TITULAR</span>
            <span id="cardExpiry">MM/YY</span>
        </div>
    </div>

    <form method="POST" action="{{ route('recibo.procesar') }}" id="paymentForm" novalidate>
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">👤 Nombre del titular</label>
                    <input type="text"
                           name="nombre"
                           id="nombre"
                           class="form-control"
                           placeholder="Ej: Juan"
                           required
                           pattern="[A-Za-záéíóúñÑ\s]{2,}"
                           title="Solo letras, mínimo 2 caracteres">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">👤 Apellidos del titular</label>
                    <input type="text"
                           name="apellidos"
                           id="apellidos"
                           class="form-control"
                           placeholder="Ej: Pérez García"
                           required
                           pattern="[A-Za-záéíóúñÑ\s]{2,}"
                           title="Solo letras, mínimo 2 caracteres">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">💳 Número de tarjeta</label>
            <div class="input-icon">
                <input type="text"
                       name="tarjeta"
                       id="tarjeta"
                       class="form-control"
                       placeholder="1234 5678 9012 3456"
                       required
                       maxlength="19"
                       pattern="[0-9\s]{13,19}"
                       title="Solo números, entre 13-16 dígitos">
                <i class="bi bi-credit-card"></i>
            </div>
            <div class="invalid-feedback"></div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">📅 Expiración (MM/YY)</label>
                    <input type="text"
                           name="expiracion"
                           id="expiracion"
                           class="form-control"
                           placeholder="12/25"
                           required
                           maxlength="5"
                           pattern="(0[1-9]|1[0-2])\/([0-9]{2})"
                           title="Formato MM/YY">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">🔒 Código de seguridad</label>
                    <input type="text"
                           name="cvv"
                           id="cvv"
                           class="form-control"
                           placeholder="123"
                           required
                           maxlength="4"
                           pattern="[0-9]{3,4}"
                           title="3 o 4 dígitos">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">📧 Email</label>
                    <input type="text"
                           name="email"
                           id="email"
                           class="form-control"
                           placeholder="correo@ejemplo.com"
                           value="{{ Auth::user()->email }}"
                           title="Formato de email válido">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">📞 Teléfono</label>
                    <input type="text"
                           name="telefono"
                           id="telefono"
                           class="form-control"
                           placeholder="2234-5678"
                           pattern="[0-9\-\s\+\(\)]{8,}"
                           title="Mínimo 8 caracteres, solo números y símbolos telefónicos">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="payment-icons">
            <img src="https://img.icons8.com/color/48/visa.png" alt="Visa">
            <img src="https://img.icons8.com/color/48/mastercard-logo.png" alt="Mastercard">
            <img src="https://img.icons8.com/color/48/amex.png" alt="Amex">
            <img src="https://img.icons8.com/color/48/paypal.png" alt="PayPal">
        </div>

        <button type="submit" class="btn btn-pagar" id="submitBtn">
            <span class="loading-spinner">
                <i class="bi bi-arrow-repeat"></i>
            </span>
            💳 Pagar ahora ($5.00)
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.querySelector('.loading-spinner');

    // Elementos del formulario
    const nombre = document.getElementById('nombre');
    const apellidos = document.getElementById('apellidos');
    const tarjeta = document.getElementById('tarjeta');
    const expiracion = document.getElementById('expiracion');
    const cvv = document.getElementById('cvv');
    const email = document.getElementById('email');
    const telefono = document.getElementById('telefono');

    // Elementos de vista previa
    const cardDisplay = document.getElementById('cardDisplay');
    const cardHolder = document.getElementById('cardHolder');
    const cardExpiry = document.getElementById('cardExpiry');

    // Formateo automático de número de tarjeta
    tarjeta.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedInputValue = value.match(/.{1,4}/g)?.join(' ');

        if (formattedInputValue) {
            e.target.value = formattedInputValue;
        }

        // Actualizar vista previa
        if (value) {
            cardDisplay.textContent = formattedInputValue || '•••• •••• •••• ••••';
        } else {
            cardDisplay.textContent = '•••• •••• •••• ••••';
        }

        validateField(e.target);
    });

    // Formateo automático de fecha de expiración
    expiracion.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');

        if (value.length >= 2) {
            value = value.substring(0,2) + '/' + value.substring(2,4);
        }

        e.target.value = value;
        cardExpiry.textContent = value || 'MM/YY';
        validateField(e.target);
    });

    // Solo números en CVV
    cvv.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
        validateField(e.target);
    });

    // Solo letras en nombres
    [nombre, apellidos].forEach(field => {
        field.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^A-Za-záéíóúñÑ\s]/g, '');

            if (field === nombre || field === apellidos) {
                const fullName = (nombre.value + ' ' + apellidos.value).trim().toUpperCase();
                cardHolder.textContent = fullName || 'NOMBRE DEL TITULAR';
            }

            validateField(e.target);
        });
    });

    // Formateo de teléfono
    telefono.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9\-\s\+\(\)]/g, '');
        validateField(e.target);
    });

    // Email básico
    email.addEventListener('input', function(e) {
        validateField(e.target);
    });

    // Validación individual de campos
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let message = '';

        switch(field.id) {
            case 'nombre':
            case 'apellidos':
                isValid = value.length >= 2 && /^[A-Za-záéíóúñÑ\s]+$/.test(value);
                message = isValid ? '' : 'Mínimo 2 letras, solo caracteres alfabéticos';
                break;

            case 'tarjeta':
                const cleanCard = value.replace(/\s/g, '');
                isValid = cleanCard.length >= 13 && cleanCard.length <= 16 && /^\d+$/.test(cleanCard);
                message = isValid ? '' : 'Debe tener entre 13-16 dígitos';
                break;

            case 'expiracion':
                const expMatch = value.match(/^(0[1-9]|1[0-2])\/([0-9]{2})$/);
                if (expMatch) {
                    const month = parseInt(expMatch[1]);
                    const year = parseInt('20' + expMatch[2]);
                    const currentDate = new Date();
                    const currentYear = currentDate.getFullYear();
                    const currentMonth = currentDate.getMonth() + 1;

                    isValid = year > currentYear || (year === currentYear && month >= currentMonth);
                    message = isValid ? '' : 'La tarjeta está vencida';
                } else {
                    isValid = false;
                    message = 'Formato MM/YY requerido';
                }
                break;

            case 'cvv':
                isValid = value.length >= 3 && value.length <= 4 && /^\d+$/.test(value);
                message = isValid ? '' : '3 o 4 dígitos requeridos';
                break;

            case 'email':
                isValid = value === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                message = isValid ? '' : 'Formato de email inválido';
                break;

            case 'telefono':
                isValid = value === '' || value.length >= 8;
                message = isValid ? '' : 'Mínimo 8 caracteres';
                break;
        }

        // Aplicar estilos
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
        }

        // Mostrar mensaje
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
        }

        return isValid;
    }

    // Validación del formulario completo
    function validateForm() {
        const requiredFields = [nombre, apellidos, tarjeta, expiracion, cvv];
        let allValid = true;

        requiredFields.forEach(field => {
            if (!validateField(field)) {
                allValid = false;
            }
        });

        // Validar campos opcionales solo si tienen contenido
        if (email.value) validateField(email);
        if (telefono.value) validateField(telefono);

        return allValid;
    }

    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (validateForm()) {
            // Mostrar loading
            submitBtn.disabled = true;
            loadingSpinner.style.display = 'inline-block';
            submitBtn.innerHTML = '<span class="loading-spinner"><i class="bi bi-arrow-repeat"></i></span> Procesando...';

            // Simular delay para efecto realista
            setTimeout(() => {
                form.submit();
            }, 1500);
        } else {
            // Hacer scroll al primer campo inválido
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }
        }
    });

    // Validación en tiempo real
    form.addEventListener('input', function() {
        if (validateForm()) {
            submitBtn.disabled = false;
        }
    });
});
</script>
@endsection
