@extends('layouts.admin')
@section('title', 'Author Inquiries')
@section('content')
    <div class="container py-4 max-w-3xl">
        <h2 class="mb-4">Author Book Inquiries</h2>
        @foreach($inquiries as $inq)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $inq->book_title }}</h5>
                    <p><strong>Author:</strong> {{ $inq->author_name }}<br>
                        <strong>Email:</strong> <a href="mailto:{{ $inq->author_email }}">{{ $inq->author_email }}</a><br>
                        <strong>Phone:</strong> <a href="tel:{{ $inq->author_phone }}">{{ $inq->author_phone }}</a>
                    </p>
                    <p><strong>Description:</strong> {{ $inq->book_description }}</p>
                    <p><a href="{{ route('admin.author.inquiries.download', $inq->id) }}">Download PDF</a></p>
                    <p>Status: <span
                            class="badge bg-{{ $inq->status == 'pending' ? 'warning' : ($inq->status == 'approved' ? 'success' : 'danger') }}">{{ ucfirst($inq->status) }}</span>
                    </p>
                    <form method="POST" action="{{ route('admin.author.inquiries.approve', $inq->id) }}"
                        style="display:inline;">
                        @csrf<button class="btn btn-success btn-sm">Approve</button></form>
                    <form method="POST" action="{{ route('admin.author.inquiries.deny', $inq->id) }}" style="display:inline;">
                        @csrf<button class="btn btn-danger btn-sm">Deny</button></form>
                </div>
            </div>
        @endforeach
    </div>
@endsection