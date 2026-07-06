@extends('layouts.admin')
@section('title', 'Contact Messages')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Page Header --}}
            <div class="page-title-box d-flex align-items-center justify-content-between mb-20">
                <div>
                    <h4 class="mb-0">Contact Messages</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Contacts</li>
                        </ol>
                    </nav>
                </div>
                <span class="badge bg-primary-transparent text-primary fs-13">
                    {{ $contacts->count() }} Messages
                </span>
            </div>

            <div class="card">
                <div class="card-header justify-between">
                    <h4><i class="ri-mail-line me-1"></i> Contact Messages</h4>
                </div>
                <div class="card-body pt-15">
                    @if($contacts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" id="contacts-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $i => $contact)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-10">
                                            <div class="avatar bg-primary-transparent text-primary"
                                                 style="width:36px;height:36px;border-radius:50%;
                                                        display:flex;align-items:center;justify-content:center;
                                                        font-weight:700;font-size:14px;">
                                                {{ strtoupper(substr($contact->name, 0, 1)) }}
                                            </div>
                                            <span class="fw-medium">{{ $contact->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $contact->email }}" class="text-primary">
                                            {{ $contact->email }}
                                        </a>
                                    </td>
                                    <td>{{ $contact->phone ?? '—' }}</td>
                                    <td>
                                        <span title="{{ $contact->message }}"
                                              style="cursor:pointer;">
                                            {{ Str::limit($contact->message, 50) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $contact->created_at->format('d M Y') }}<br>
                                            {{ $contact->created_at->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button"
                                                    class="btn-icon btn-success-light view-message-btn"
                                                    title="View"
                                                    data-name="{{ $contact->name }}"
                                                    data-email="{{ $contact->email }}"
                                                    data-phone="{{ $contact->phone ?? '—' }}"
                                                    data-message="{{ $contact->message }}"
                                                    data-date="{{ $contact->created_at->format('d M Y, h:i A') }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#messageModal">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            <form action="{{ route('admin.contacts.destroy', $contact->id) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Delete this message?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-icon btn-danger-light" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="ri-mail-line" style="font-size:48px;color:#ddd;"></i>
                        <p class="text-muted mt-3">No contact messages yet.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Message Modal --}}
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-mail-line me-2"></i>
                    Contact Message
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width:100px;">Name</th>
                        <td id="modal-name"></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><a id="modal-email" href="#"></a></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td id="modal-phone"></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td id="modal-date"></td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td id="modal-message" style="white-space:pre-wrap;"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <a id="modal-reply-btn" href="#" class="btn btn-primary btn-sm">
                    <i class="ri-reply-line me-1"></i> Reply via Email
                </a>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.view-message-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var email = this.dataset.email;
        document.getElementById('modal-name').textContent    = this.dataset.name;
        document.getElementById('modal-email').textContent   = email;
        document.getElementById('modal-email').href          = 'mailto:' + email;
        document.getElementById('modal-phone').textContent   = this.dataset.phone;
        document.getElementById('modal-date').textContent    = this.dataset.date;
        document.getElementById('modal-message').textContent = this.dataset.message;
        document.getElementById('modal-reply-btn').href      = 'mailto:' + email;
    });
});
</script>
@endpush