<?php $__env->startComponent('mail::message'); ?>
# Ticket Status Updated

The status of your support ticket has been updated.

<?php $__env->startComponent('mail::panel'); ?>
**Ticket #:** <?php echo new \Illuminate\Support\EncodedHtmlString($ticket->ticket_number); ?>

**Title:** <?php echo new \Illuminate\Support\EncodedHtmlString($ticket->title); ?>

**Previous Status:** <?php echo new \Illuminate\Support\EncodedHtmlString(ucfirst($oldStatus)); ?>

**New Status:** <?php echo new \Illuminate\Support\EncodedHtmlString(ucfirst($newStatus)); ?>

<?php echo $__env->renderComponent(); ?>

<?php $__env->startComponent('mail::button', ['url' => config('app.url')]); ?>
View Ticket
<?php echo $__env->renderComponent(); ?>

Thanks,
<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?><?php /**PATH C:\Users\Jyoti Pandey\Downloads\internship-backend\internship-backend\resources\views/emails/tickets/status-changed.blade.php ENDPATH**/ ?>