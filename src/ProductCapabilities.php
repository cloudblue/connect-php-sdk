<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

class ProductCapabilities extends Model
{

    /**
     * @var \Connect\Product\Capabilities\PPU
     */
    protected $ppu;

    /**
     * @var \Connect\Product\Capabilities\Reservation
     */
    protected $reservation;

    /**
     * @var \Connect\Product\Capabilities\Cart
     */
    protected $cart;

    /**
     * @var \Connect\Product\Capabilities\Tiers | null
     */
    protected $tiers;

    /**
     * @var bool
     */
    public $hold;

    /**
     * @var ProductCapabilityBilling
     */
    public $billing;

    public function setPpu($ppu)
    {
        $this->ppu = Model::modelize('PCPPU', $ppu);
    }

    public function setReservation($reservation)
    {
        $this->reservation = Model::modelize('PCReservation', $reservation);
    }

    public function setCart($cart)
    {
        $this->cart = Model::modelize('PCCart', $cart);
    }

    public function setTiers($tiers)
    {
        $this->tiers = Model::modelize('PCTiers', $tiers);
    }

    public function setBilling($billing)
    {
        $this->billing = Model::modelize('PCBilling', $billing);
    }
}
