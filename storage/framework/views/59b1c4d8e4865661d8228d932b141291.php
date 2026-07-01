<?php $__env->startComponent('mail::message'); ?>
# New Comment Added

A new comment has been added to your support ticket.

<?php $__env->startComponent('mail::panel'); ?>
**Ticket #:** <?php echo new \Illuminate\Support\EncodedHtmlString($ticket->ticket_number); ?>

**Title:** <?php echo new \Illuminate\Support\EncodedHtmlString($ticket->title); ?>

**Comment By:** <?php echo new \Illuminate\Support\EncodedHtmlString($comment->user->name ?? 'N/A'); ?>

**Comment:** <?php echo new \Illuminate\Support\EncodedHtmlString($comment->body); ?>

<?php echo $__env->renderComponent(); ?>

<?php $__env->startComponent('mail::button', ['url' => config('app.url')]); ?>
View Ticket
<?php echo $__env->renderComponent(); ?>

Thanks,
<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?><?php /**PATH C:\Users\Jyoti Pandey\Downloads\internship-backend\internship-backend\resources\views/emails/tickets/comment-added.blade.php ENDPATH**/ ?>