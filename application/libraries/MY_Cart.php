<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Cart extends CI_Cart {
    
    function update_options($items = array())
    {
        // Was any cart data passed?
        if ( ! is_array($items) OR count($items) == 0)
        {
            return FALSE;
        }

        // You can either update a single product using a one-dimensional array,
        // or multiple products using a multi-dimensional one.  The way we
        // determine the array type is by looking for a required array key named "id".
        // If it's not found we assume it's a multi-dimensional array
        
        $save_cart = FALSE;
        if (isset($items['rowid']) AND is_array($items['options']))
        {
            if ($this->_update_options($items) == TRUE)
            {
                $save_cart = TRUE;
            }
        }
        else
        {
            foreach ($items as $val)
            {
                if (is_array($val) AND isset($val['rowid']) AND is_array($val['options']))
                {
                    if ($this->_update_options($val) == TRUE)
                    {
                        $save_cart = TRUE;
                    }
                }
            }
        }

        // Save the cart data if the insert was successful
        if ($save_cart == TRUE)
        {
            $this->_save_cart();
            return TRUE;
        }

        return FALSE;
    }

    
    function _update_options($items = array())
    {
        
        // Without these array indexes there is nothing we can do
        if ( ! isset($items['rowid']) OR ! isset($this->_cart_contents[$items['rowid']]))
        {
            return FALSE;
        }

        
        
        if(isset($items['qty']))
        {
            // Prep the quantity
            $items['qty'] = preg_replace('/([^0-9])/i', '', $items['qty']);
            if ( ! is_numeric($items['qty']))
            {
                return FALSE;
            }
            elseif ($items['qty'] == 0)
            {
                unset($this->_cart_contents[$items['rowid']]);
            }
            elseif ($items['qty'] != $this->_cart_contents[$items['rowid']]['qty'])
            {
                $this->_cart_contents[$items['rowid']]['qty'] = $items['qty'];
            }
        }
        // Loops through the new options, checks to see that the object
        // in the cart has the corresponding option already set and if so;
        // overwrites the old value with the new one.
        
        foreach($items['options'] as $option => $new_value)
        {
            if(array_key_exists($option, $this->_cart_contents[$items['rowid']]['options']))
            {
                $this->_cart_contents[$items['rowid']]['options'][$option] = $new_value;
            }
            else
            {
                return FALSE;
            }
        }

        return TRUE;
    }

}