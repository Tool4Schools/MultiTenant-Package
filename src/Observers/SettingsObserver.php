<?php

namespace Tools4Schools\MultiTenant\Observers;


use Tools4Schools\MultiTenant\Facades\TenantManager;
use Tools4Schools\Settings\Models\Setting;

class SettingsObserver
{
    /**
     * Handle the setting "created" event.
     *
     * @param  \Tools4Schools\Settings\Models\Setting  $setting
     * @return void
     */
    public function creating(Setting $setting)
    {
        $setting->tenant_id = TenantManager::tenant()->id;
    }

    /**
     * Handle the setting "updated" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function updated(Setting $setting)
    {
        //
    }

    /**
     * Handle the setting "deleted" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function deleted(Setting $setting)
    {
        //
    }

    /**
     * Handle the setting "restored" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function restored(Setting $setting)
    {
        //
    }

    /**
     * Handle the setting "force deleted" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function forceDeleted(Setting $setting)
    {
        //
    }
}
