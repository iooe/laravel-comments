<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('comments.store') }}">
            @csrf
            <input type="hidden" name="commentable_encrypted_key" value="{{ $model->getEncryptedKey() }}"/>

            <div class="form-group">
                <label for="message">Enter your message here:</label>
                <textarea class="form-control @if($errors->has('message')) is-invalid @endif" name="message"
                          rows="3"></textarea>
                <div class="invalid-feedback">
                    Your message is required.
                </div>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">Submit</button>
        </form>
    </div>
</div>
<br/>