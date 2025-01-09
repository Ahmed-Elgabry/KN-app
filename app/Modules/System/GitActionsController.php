<?php

namespace App\Modules\System;

use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Auth;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\App;
use Symfony\Component\Process\Process;

class GitActionsController extends SystemController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (App::environment('production')) {
            abort(404);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/gitniceone/wms/branches');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: application/vnd.github+json",
            "Authorization: Bearer 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",
            "X-GitHub-Api-Version: 2022-11-28",
            "User-Agent:ActionsAyeshApp"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $branches_List = curl_exec($ch);
        curl_close($ch);
        $branches = json_decode($branches_List);
        $runner = new \CzProject\GitPhp\Runners\CliRunner(env('CLI_RUNNER','echo "p@ssw0rd" | sudo -S -u root git'));
        $git = new \CzProject\GitPhp\Git($runner);
        // /var/www/html/new-admin
        // "C:/laragon/www/wms"
        try {
            $repo = $git->open(env('PROJECT_PATH',"/var/www/html/new-admin"));
            $repo->execute('pull');
            $current_branch = $repo->getCurrentBranchName();
        } catch (\CzProject\GitPhp\GitException $e) {
            return $this->fail($e->getRunnerResult()->toText());
        }
        return view('system.git-actions.index', compact('branches', 'current_branch'));
    }

    public function checkout_branch($branch_name)
    {
        $runner = new \CzProject\GitPhp\Runners\CliRunner(env('CLI_RUNNER','echo "p@ssw0rd" | sudo -S -u root git'));
        $git = new \CzProject\GitPhp\Git($runner);
        // /var/www/html/new-admin
        $repo = $git->open(env('PROJECT_PATH',"/var/www/html/new-admin"));
        try {
            if($repo->hasChanges()){
                $repo->addAllChanges();
                $repo->commit('commit Server');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        try {
            $repo->checkout($branch_name);

            if($repo->hasChanges()){
                $repo->addAllChanges();
                $repo->commit('commit Server');
            }
            $repo->execute('pull');

        } catch (\CzProject\GitPhp\GitException $e) {
            return $this->fail($e->getRunnerResult()->toText());
        }
        exec('echo "p@ssw0rd" | sudo -S -u root php /var/www/html/new-admin/artisan optimize:clear',$output,$code);

        exec('echo "p@ssw0rd" | sudo -S -u root composer dump-autoload --working-dir=/var/www/html/new-admin',$output2,$code2);

        return $this->success(__('Success !'),['url' => route('system.git-branches'),'reload' => 'reload' ]);
    }


    
}
