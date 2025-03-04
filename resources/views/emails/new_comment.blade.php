<!DOCTYPE html>
<html>
<head>
    <title>New Comment Notification</title>
</head>
<body>
    <h1>Hello, {{ $comment->commentable->name ?? 'User' }}!</h1>
    <p>You have received a new comment from <strong>{{ $commenter }}</strong>:</p>
    <blockquote>{{ $commentContent }}</blockquote>
    <p>Check it out on your profile or blog.</p>
    <p>Thanks,</p>
    <p>Blog Website Team</p>
</body>
</html>
