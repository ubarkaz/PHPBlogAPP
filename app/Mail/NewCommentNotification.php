<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCommentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function build()
    {
        return $this->to($this->comment->commentable->email) // Send email to blog/user owner
                    ->subject('New Comment on Your Profile/Blog')
                    ->view('emails.new_comment')
                    ->with([
                        'comment' => $this->comment,
                        'commenter' => $this->comment->user->name,
                        'commentContent' => $this->comment->content,
                    ]);
    }
}