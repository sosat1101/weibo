@can('follow', $user)
    <div class="text-center mt-2 mb-4">
        @if (Auth::user()->isFollowing($user->id))
            <form action="{{ route('followers.destroy', $user->id) }}" method="POST">
                 @csrf
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-sm btn-outline-primary">Cancel Follow</button>
            </form>
        @else
            <form action="{{ route('followers.store', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary">Follow</button>
            </form>
        @endif
    </div>
@endcan
