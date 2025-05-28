<?php

namespace App\Services;

interface MercadoPagoInterface
{
    public function crearOrden(array $datos);
}
