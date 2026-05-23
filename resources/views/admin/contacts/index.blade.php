@extends('layouts.admin')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Contact Form Submissions</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cart-main-area ptb-80">
    <div class="container">
        <div class="table-content table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr>
                        <td>{{ $contact->id }}</td>
                        <td>{{ $contact->name }}</td>
                        <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                        <td>{{ $contact->phone ?? '—' }}</td>
                        <td>{{ Str::limit($contact->message, 60) }}</td>
                        <td>{{ $contact->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#999;padding:30px;">
                            No contact submissions yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
