{{-- Core Blade examples for erag/laravel-datetime-format --}}

<section>
    <h2>User timestamps</h2>

    <dl>
        <dt>Created</dt>
        <dd>@datetime($user->created_at)</dd>

        <dt>Updated</dt>
        <dd>@dateTimeFormat($user->updated_at)</dd>

        <dt>Verified on</dt>
        <dd>@dateFormat($user->email_verified_at)</dd>

        <dt>Login time</dt>
        <dd>@timeFormat($lastLoginAt)</dd>
    </dl>
</section>
