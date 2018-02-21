<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="merchant" value="<?php echo $merchant; ?>" />
  <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <input type="hidden" name="account[order_id]" value="<?php echo $account; ?>" />
  <input type="hidden" name="callback" value="<?php echo $callback; ?>">
  <input type="hidden" name="description" value="<?php echo $description; ?>">
  <div class="buttons">
    <div class="right">      
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>    
</form>
