<a href="{{route('users.following', $user->id)}}">
    <strong id="following" class="stat">
        {{ count($user->followings) }}
    </strong>
    关注
</a>
<a href="{{route('users.follower', $user->id)}}">
    <strong id="followers" class="stat">
        {{ count($user->followers) }}
    </strong>
    粉丝
</a>
<a href="#">
    <strong id="statuses" class="stat">
        {{ $user->status()->count() }}
    </strong>
    微博
</a>
