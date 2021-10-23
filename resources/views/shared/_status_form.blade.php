<form action="{{ route('status.store') }}" method="POST">
    @include('shared._errors')
    @csrf
    <textarea class="form-control" rows="3" placeholder="talk some" name="content">
        {{ old('content') }}
    </textarea>
    <div class="text-right">
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </div>
</form>
