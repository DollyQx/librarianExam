@extends('layouts.app')

@section('title', 'Studyly Membership | ₹49 / 30 Days')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if(isset($activeMembership) && $activeMembership)
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-success bg-opacity-10 border-start border-5 border-success">
                    <div class="card-body p-4 text-start">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <i class="fas fa-check-circle text-success fs-2"></i>
                            <div>
                                <h4 class="fw-bold text-dark mb-0">आपकी Membership सक्रिय (Active) है!</h4>
                                <p class="text-muted mb-0 small">आप सभी "Membership Required" मॉक टेस्ट, PDF नोट्स और वीडियो लेक्चर्स का लाभ उठा सकते हैं।</p>
                            </div>
                        </div>
                        <div class="mt-3 p-3 bg-white rounded-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block">सक्रियता अवधि (Validity):</small>
                                <strong class="text-dark">{{ $activeMembership->starts_at->format('d M Y') }} — {{ $activeMembership->expires_at->format('d M Y') }}</strong>
                            </div>
                            <span class="badge bg-success fs-6">सक्रिय</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-gradient p-4 text-center border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">
                        <i class="fas fa-crown me-1"></i> Studyly Membership
                    </span>
                    <h2 class="fw-bold text-white mb-1">पूरी तैयारी, सिर्फ ₹49 में!</h2>
                    <p class="text-white-50 mb-0">30 दिनों के लिए Full Mock Tests, Quiz Series, PDF Notes, Video Lectures और बहुत कुछ।</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <span class="display-3 fw-extrabold text-dark">₹49</span>
                        <span class="text-muted fs-5"> / 30 दिन</span>
                    </div>

                    <h5 class="fw-bold text-dark mb-3">Membership के मुख्य लाभ:</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3 mb-4">
                        <li class="d-flex align-items-start gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle fs-6">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <strong class="text-dark">सभी Premium Mock Tests एवं Quiz Series:</strong>
                                <span class="text-muted small d-block">Bihar LET एवं Bihar Librarian के सभी फुल मॉक टेस्ट हल करें।</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle fs-6">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <strong class="text-dark">PDF नोट्स (ऑनलाइन पठन):</strong>
                                <span class="text-muted small d-block">अध्यायवार एवं विषयवार सुरक्षित PDF स्टडी मटेरियल पढ़ें।</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle fs-6">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <strong class="text-dark">वीडियो लेक्चर्स एवं स्पेशल क्लासेस:</strong>
                                <span class="text-muted small d-block">महत्वपूर्ण विषयों की विस्तृत वीडियो क्लासेस।</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle fs-6">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <strong class="text-dark">स्वचालित नया कंटेंट एक्सेस:</strong>
                                <span class="text-muted small d-block">आपकी 30 दिन की वैधता के दौरान जोड़े गए नए सभी टेस्ट्स स्वतः अनलॉक होंगे।</span>
                            </div>
                        </li>
                    </ul>

                    <div id="payment-error-alert" class="alert alert-danger d-none" role="alert"></div>

                    @auth
                        <button id="pay-membership-btn" onclick="startRazorpayPayment()" class="btn btn-warning btn-lg w-100 fw-bold text-dark py-3 shadow-sm rounded-3">
                            <i class="fas fa-lock me-2"></i> {{ isset($activeMembership) && $activeMembership ? 'Renew Membership (₹49 में 30 दिन बढ़ाएं)' : 'अभी Membership लें (₹49)' }}
                        </button>
                    @else
                        <div class="p-3 bg-light rounded-3 text-center mb-3">
                            <p class="text-muted mb-2">Membership लेने के लिए पहले अपना अकाउंट लॉगिन या रजिस्टर करें:</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('login') }}" class="btn btn-brand-outline fw-bold">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-brand-primary fw-bold">Register</a>
                            </div>
                        </div>
                    @endauth

                    <div class="text-center mt-3 text-muted extra-small">
                        <i class="fas fa-shield-alt text-success me-1"></i> 100% सुरक्षित भुगतान Razorpay द्वारा संचालित।
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@auth
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function startRazorpayPayment() {
    const btn = document.getElementById('pay-membership-btn');
    const errorAlert = document.getElementById('payment-error-alert');
    errorAlert.classList.add('d-none');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ऑर्डर तैयार हो रहा है...';

    fetch("{{ route('membership.order') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            throw new Error(data.error || 'ऑर्डर बनाने में त्रुटि हुई।');
        }

        const options = {
            "key": data.key,
            "amount": data.amount,
            "currency": data.currency,
            "name": "BIHAR LET/Librarian Exam",
            "description": "Studyly Membership - 30 Days Access",
            "image": "https://cdn-icons-png.flaticon.com/512/2997/2997295.png",
            "order_id": data.razorpay_order_id,
            "handler": function (response) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> भुगतान सत्यापित किया जा रहा है...';
                verifyPayment(data.order_id, response);
            },
            "prefill": {
                "name": data.user.name,
                "email": data.user.email,
                "contact": data.user.phone
            },
            "theme": {
                "color": "#2563eb"
            },
            "modal": {
                "ondismiss": function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-lock me-2"></i> अभी Membership लें (₹49)';
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock me-2"></i> पुन: प्रयास करें';
        errorAlert.textContent = err.message;
        errorAlert.classList.remove('d-none');
    });
}

function verifyPayment(orderId, razorpayData) {
    fetch("{{ route('membership.verify') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            membership_order_id: orderId,
            razorpay_payment_id: razorpayData.razorpay_payment_id,
            razorpay_order_id: razorpayData.razorpay_order_id,
            razorpay_signature: razorpayData.razorpay_signature
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = "{{ route('student.dashboard') }}?membership_success=1";
        } else {
            throw new Error(data.error || 'भुगतान सत्यापन विफल हो गया।');
        }
    })
    .catch(err => {
        const btn = document.getElementById('pay-membership-btn');
        const errorAlert = document.getElementById('payment-error-alert');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock me-2"></i> पुन: प्रयास करें';
        errorAlert.textContent = err.message;
        errorAlert.classList.remove('d-none');
    });
}
</script>
@endauth
@endsection
