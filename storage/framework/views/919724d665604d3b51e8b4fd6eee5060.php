<?php $__env->startComponent('mail::message'); ?>
# New Ticket Created

A new support ticket has been submitted.

<?php $__env->startComponent('mail::panel'); ?>
**Ticket #:** <?php echo new \Illuminate\Support\EncodedHtmlString($ticket->ticket_number); ?>

**Title:** <?php echo new \Illuminate\Support\EncodedHtmlString($ticket->title); ?>

**Priority:** <?php echo new \Illuminate\Support\EncodedHtmlString(ucfirst($ticket->priority)); ?>

**Status:** <?php echo new \Illuminate\Support\EncodedHtmlString(ucfirst($ticket->status)); ?>

**Created By:** <?php echo new \Illuminate\Support\EncodedHtmlString($ticket->user->name ?? 'N/A'); ?>

<?php echo $__env->renderComponent(); ?>

<?php $__env->startComponent('mail::button', ['url' => config('app.url')]); ?>
View Ticket
<?php echo $__env->renderComponent(); ?>

Thanks,
<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?><?php /**PATH C:\Users\Jyoti Pandey\Downloads\internship-backend\internship-backend\resources\views/emails/tickets/created.blade.php ENDPATH**/ ?>