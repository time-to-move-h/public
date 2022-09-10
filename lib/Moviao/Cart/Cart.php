<?php
declare(strict_types=1);
namespace Moviao\Cart;
/**
 * Cart: A very simple PHP cart library.
 *
 * Copyright (c) 2020-2021 Moviao
 *
 */
class Cart
{
    /**
     * An unique ID for the cart.
     *
     * @var string
     */
    protected $cartId;

    /**
     * Timeout for the cart
     * @var string
     */
    protected $cart_checkout_timeout = 'cart_checkout_timeout';

    /**
     * Maximum item allowed in the cart.
     *
     * @var int
     */
    protected $cartMaxItem = 0;

    /**
     * Maximum quantity of a item allowed in the cart.
     *
     * @var int
     */
    protected $itemMaxQuantity = 0;

    /**
     * Enable or disable cookie.
     *
     * @var bool
     */
    protected $useCookie = false;

    /**
     * A collection of cart items.
     *
     * @var array
     */
    private $items = [];

    /**
     * @var null
     */
    private $timeout = null;

    /**
     * Initialize cart.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (!session_id()) {
            session_start();
        }

        if (isset($options['cartMaxItem']) && preg_match('/^\d+$/', (string) $options['cartMaxItem'])) {
            $this->cartMaxItem = $options['cartMaxItem'];
        }

        if (isset($options['itemMaxQuantity']) && preg_match('/^\d+$/', (string) $options['itemMaxQuantity'])) {
            $this->itemMaxQuantity = $options['itemMaxQuantity'];
        }

        if (isset($options['useCookie']) && $options['useCookie']) {
            $this->useCookie = true;
        }

        if (isset($options['timeout']) && $options['timeout'] instanceof \DateTime) {
            $this->timeout = $options['timeout'];
        }

        $this->cartId = md5((isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : 'SimpleCart') . '_cart';

        $this->read();
    }

    /**
     * Get items in  cart.
     *
     * @return array
     */
    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * Check if the cart is empty.
     *
     * @return bool
     */
    public function isEmpty() : bool
    {
        return empty(array_filter($this->items));
    }

    /**
     * Get the total of item in cart.
     *
     * @return int
     */
    public function getTotalItem() : int
    {
        $total = 0;

        foreach ($this->items as $items) {
            foreach ($items as $item) {
                ++$total;
            }
        }

        return $total;
    }

    /**
     * Get the total of item quantity in cart.
     *
     * @return int
     */
    public function getTotalQuantity() : int
    {
        $quantity = 0;

        foreach ($this->items as $items) {
            foreach ($items as $item) {
                $quantity += $item['quantity'];
            }
        }

        return $quantity;
    }

    /**
     * Get the sum of a attribute from cart.
     *
     * @param string $attribute
     *
     * @return int
     */
    public function getAttributeTotal($attribute = 'price') : int
    {
        $total = 0;

        foreach ($this->items as $items) {
            foreach ($items as $item) {
                if (isset($item['attributes'][$attribute])) {
                    $total += $item['attributes'][$attribute] * $item['quantity'];
                }
            }
        }

        return $total;
    }

    /**
     * Remove all items from cart.
     */
    public function clear() : void
    {
        $this->items = [];
        $this->write();
    }

    /**
     * Check if a item exist in cart.
     *
     * @param string $id
     * @param array  $attributes
     *
     * @return bool
     */
    public function isItemExists($id, $attributes = []) : bool
    {
        $attributes = (is_array($attributes)) ? array_filter($attributes) : [$attributes];

        if (isset($this->items[$id])) {
            $hash = md5(json_encode($attributes));
            foreach ($this->items[$id] as $item) {
                if ($item['hash'] == $hash) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Add item to cart.
     *
     * @param string $id
     * @param int    $quantity
     * @param array  $attributes
     *
     * @return bool
     */
    public function add(string $id, ?int $quantity = null, $attributes = []) : bool
    {
        $quantity = (null !== $quantity && is_numeric($quantity)) ? $quantity : 1; // preg_match('/^\d+$/', $quantity)
        $attributes = is_array($attributes) ? array_filter($attributes) : [$attributes];
        $hash = md5(json_encode($attributes));

        if (isset($this->items[$id])) {
            $index = 0;
            foreach ($this->items[$id] as $item) {
                if ($item['hash'] == $hash) {
                    $this->items[$id][$index]['quantity'] += $quantity;
                    $this->items[$id][$index]['quantity'] = ($this->itemMaxQuantity < $this->items[$id][$index]['quantity']) ? $this->itemMaxQuantity : $this->items[$id][$index]['quantity'];

                    $this->write();

                    return true;
                }
                ++$index;
            }
        }

        $this->items[$id][] = [
            'id'         => $id,
            'quantity'   => ($quantity > $this->itemMaxQuantity) ? $this->itemMaxQuantity : $quantity,
            'hash'       => $hash,
            'attributes' => $attributes,
        ];

        $this->write();

        return true;
    }

    /**
     * Update item quantity.
     *
     * @param string $id
     * @param int    $quantity
     * @param array  $attributes
     *
     * @return bool
     */
    public function update($id, $quantity = 1, $attributes = []) : bool
    {
        $quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;

        if ($quantity == 0) {
            $this->remove($id, $attributes);

            return true;
        }

        if (isset($this->items[$id])) {
            $hash = md5(json_encode(array_filter($attributes)));

            $index = 0;
            foreach ($this->items[$id] as $item) {
                if ($item['hash'] == $hash) {
                    $this->items[$id][$index]['quantity'] = $quantity;
                    $this->items[$id][$index]['quantity'] = ($this->itemMaxQuantity < $this->items[$id][$index]['quantity']) ? $this->itemMaxQuantity : $this->items[$id][$index]['quantity'];

                    $this->write();

                    return true;
                }
                ++$index;
            }
        }

        return false;
    }

    /**
     * Remove item from cart.
     *
     * @param string $id
     * @param array  $attributes
     *
     * @return bool
     */
    public function remove($id, $attributes = []) : bool
    {
        if (!isset($this->items[$id])) {
            return false;
        }

        if (empty($attributes)) {
            unset($this->items[$id]);

            $this->write();

            return true;
        }
        $hash = md5(json_encode(array_filter($attributes)));

        foreach ($this->items[$id] as $index => $item) {
            if ($item['hash'] == $hash) {
                unset($this->items[$id][$index]);

                $this->write();

                return true;
            }
        }

        return false;
    }

    /**
     * Destroy cart session.
     */
    public function destroy() : void
    {
        $this->items = [];

        if ($this->useCookie) {
            setcookie($this->cartId, '', -1);
        } else {
            unset($_SESSION[$this->cartId],$_SESSION[$this->cart_checkout_timeout]);
        }
    }

    /**
     * Read items from cart session.
     */
    private function read() : void
    {
        $this->items = ($this->useCookie) ? json_decode((isset($_COOKIE[$this->cartId])) ? $_COOKIE[$this->cartId] : '[]', true) : json_decode((isset($_SESSION[$this->cartId])) ? $_SESSION[$this->cartId] : '[]', true);
        $this->timeout = isset($_SESSION[$this->cart_checkout_timeout]) ? $_SESSION[$this->cart_checkout_timeout] : null;
    }

    /**
     * Write changes into cart session.
     */
    private function write() : void
    {
        if ($this->useCookie) {
            setcookie($this->cartId, json_encode(array_filter($this->items)), time() + 604800);
        } else {
            $_SESSION[$this->cartId] = json_encode(array_filter($this->items));
            $_SESSION[$this->cart_checkout_timeout] = $this->timeout !== null ? $this->timeout : null;
        }
    }

    public function setTimeout(\DateTime $timeout) {
        $this->timeout = $timeout;
    }

    public function getTimeout() {
        return $this->timeout;
    }

    public function isTimeoutDefined() : bool {
        return ($this->timeout != null);
    }
}