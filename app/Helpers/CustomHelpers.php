<?php

declare(strict_types = 1);

if (! function_exists('checkTenantId')) {
    function checkTenantId(): bool
    {
        return session()->has('tenant_id') && session('tenant_id') !== null;
    }
}

if (! function_exists('clearString')) {
    function clearString($str)
    {
        $str = preg_replace('/[áàãâä]/ui', 'a', (string) $str);
        $str = preg_replace('/[éèêë]/ui', 'e', $str);
        $str = preg_replace('/[íìîï]/ui', 'i', $str);
        $str = preg_replace('/[óòõôö]/ui', 'o', $str);
        $str = preg_replace('/[úùûü]/ui', 'u', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);
        // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
        $str = preg_replace('/[^a-z0-9]/i', '', $str); // ideia do Bacco :)

        return preg_replace('/_+/', '', $str);
    }

    if(! function_exists('maskCnpj')) {
        function maskCnpj($cnpj): string
        {
            return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
        }
    }
}
