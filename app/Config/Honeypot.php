<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Honeypot extends BaseConfig
{
    /**
     * Makes Honeypot visible or not to human
     */
    public bool $hidden = true;

    /**
     * Honeypot Label Content
     */
    public string $label = 'Fill This Field';

    /**
     * Honeypot Field Name
     */
    public string $name = 'u_city';//to make it believe for the bot as if it were user city

    /**
     * Honeypot HTML Template
     */
    public string $template = '<label class="form-label">{label}</label><br><input type="text" class="form-control" name="{name}" value="">';

    /**
     * Honeypot container
     *
     * If you enabled CSP, you can remove `style="display:none"`.
     */
    public string $container = '<div class="form-group u_city-wrapper">{template}</div>';

    /**
     * The id attribute for Honeypot container tag
     *
     * Used when CSP is enabled.
     */
    public string $containerId = 'hpc';
}
