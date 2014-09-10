<?php namespace DMA\Friends;

use Backend;
use Illuminate\Support\Facades\Event;
use Rainlab\User\Models\User as User;
use System\Classes\PluginBase;

use App;
use Illuminate\Foundation\AliasLoader;

/**
 * Friends Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Friends',
            'description' => 'A platform for users to earn badges and redeem rewards',
            'author'      => 'Dallas Museum of Art',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerPermissions()
    {
        return [
            'dma.friends.access_admin'  => ['label' => 'Manage Friends'],
        ];
    }

    public function registerSettings()
    {
        return [
            'locations' => [
                'label' => 'Locations',
                'description'   => 'Manage the kiosk locations',
                'category'      => 'Friends',
                'icon'          => 'icon-location-arrow',
                'url'           => Backend::url('dma/friends/locations'),
                'order'         => 0,
            ],  
            'activityTypes' => [
                'label' => 'Activity Types',
                'description'   => 'Manage the activity types that are available to steps',
                'category'      => 'Friends',
                'icon'          => 'icon-square',
                'url'           => Backend::url('dma/friends/activitytypes'),
                'order'         => 10,
            ],
            'activityTriggerTypes' => [
                'label' => 'Activity Trigger Types',
                'description'   => 'Manage the activity trigger types that are available to steps',
                'category'      => 'Friends',
                'icon'          => 'icon-square',
                'url'           => Backend::url('dma/friends/activitytriggertypes'),
                'order'         => 20,
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'friends' => [
                'label'         => 'Friends',
                'url'           => Backend::url('dma/friends/badges'),
                'icon'          => 'icon-users',
                'permissions'   => ['dma.friends.*'],
                'order'         => 500,
                'sideMenu'  => [
                    'badges'    => [
                        'label'     => 'Badges',
                        'icon'      => 'icon-shield',
                        'url'       => Backend::url('dma/friends/badges'),
                        'permissions'   => ['dma.friends.access_admin'],
                    ],
                    'rewards'   => [
                        'label'     => 'Rewards',
                        'icon'      => 'icon-money',
                        'url'       => Backend::url('dma/friends/rewards'),
                    ],
                    'activities'   => [
                        'label'     => 'Activities',
                        'icon'      => 'icon-child',
                        'url'       => Backend::url('dma/friends/activities'),
                    ],  
                    'activitylogs'   => [
                        'label'     => 'Activity Logs',
                        'icon'      => 'icon-rocket',
                        'url'       => Backend::url('dma/friends/activitylogs'),
                    ],
                    
                ]
            ]
        ];
    }

    public function boot()
    {
    	// Register ServiceProviders
    	App::register('\EllipseSynergie\ApiResponse\Laravel\ResponseServiceProvider');
    	
        // Extend the user model to support our custom metadata
        User::extend(function($model) {
            $model->hasOne['metadata']      = ['DMA\Friends\Models\Usermeta'];
            $model->hasMany['badges']       = ['DMA\Friends\Models\Badge', 'dma_friends_user_badges', 'user_id', 'badge_id'];
            $model->hasMany['steps']        = ['DMA\Friends\Models\Step', 'dma_friends_user_steps', 'user_id', 'step_id'];
            $model->hasMany['activityLogs'] = ['DMA\Friends\Models\ActivityLog'];
        });

        Event::listen('backend.form.extendFields', function($widget) {
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) return;
            if ($widget->getContext() != 'update') return;

            $widget->addFields([
                'metadata[first_name]' => [
                    'label' => 'First Name',
                    'tab'   => 'Metadata',
                ],  
                'metadata[last_name]' => [
                    'label' => 'Last Name',
                    'tab'   => 'Metadata',
                ], 
                'metadata[points]' => [
                    'label' => 'Points',
                    'tab'   => 'Metadata',
                ],
                'metadata[email_optin]' => [
                    'label' => 'Email Opt-in',
                    'type'  => 'checkbox',
                    'tab'   => 'Metadata',
                ],
                'metadata[current_member]' => [
                    'label' => 'Current member?',
                    'type'  => 'checkbox',
                    'tab'   => 'Metadata',
                ],
                'metadata[current_member_number]' => [
                    'label' => 'Current Member Number',
                    'tab'   => 'Metadata',
                ],
            ], 'primary');
        }); 

        Event::listen('backend.list.extendColumns', function($widget) {
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) return;

            $widget->addColumns([
                'full_name' => [
                    'label'         => 'Full Name',
                    'relation'      => 'metadata',
                    'sortable'   => false,
                    'select'        => "concat(first_name, ' ', last_name)", 
                    'searchable'    => true,
                ],
                'first_name' => [
                    'label'         => 'First Name',
                    'relation'      => 'metadata',
                    'select'        => '@first_name',
                    'searchable'    => true,
                ],  
                'last_name' => [
                    'label'         => 'Last Name',
                    'relation'      => 'metadata',
                    'select'        => '@last_name',
                    'searchable'    => true,
                ], 
                'points' => [
                    'label'     => 'Points',
                    'relation'  => 'metadata',
                    'select'    => '@points',
                ], 
                'zip' => [
                    'label' => 'Zip',
                ],
            ]); 
        });

    }

    public function registerFormWidgets()
    {
        return [
            'DMA\Friends\FormWidgets\RequiredSteps' => [
                'label' => 'Required Steps',
                'alias' => 'requiredsteps',
            ]
        ];   
    }

    public function register()
    {
        $this->registerConsoleCommand('friends.sync-friends-data', 'DMA\Friends\Console\SyncFriendsDataCommand');
        $this->registerConsoleCommand('friends.sync-friends-relations', 'DMA\Friends\Console\SyncFriendsRelationsCommand');
    } 

    public function registerReportWidgets()
    {   
        return [
            'DMA\Friends\ReportWidgets\FriendsToolbar'=>[
                'label'   => 'Friends Toolbar',
                'context' => 'dashboard'
            ],  
        ];  
    } 

}
