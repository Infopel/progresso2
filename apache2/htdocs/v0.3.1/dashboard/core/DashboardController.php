<?php

namespace App\dashboard;
use \PDO;
use \PDOException;
use Carbon\Carbon;
use Symfony\Component\Yaml\Yaml;

class DashboardController {

    protected $conn;
    protected $user;
    protected $defaultProject; // default parent project for querys

    public function __construct($conn, $user = null, $defaultProject = 12)
    {
        $this->conn = $conn;
        $this->user = $user;
        $this->defaultProject = $defaultProject;

        setlocale(LC_TIME, 'pt-Br', 'pt-Br.utf-8', 'pt-Br.utf-8');
        \Carbon\Carbon::setLocale('pt-Br');
    }

    public function index()
    {
        $data = array(
            'overview' => $this->overview(),
            'issues' => $this->issues(),
            'hotTasks' => $this->hotTasks(),
            'proProvincia' => $this->prov_programas(),
            '_projectsProv' => $this->dataProvincias(),
            'activities' => $this->recent_activities(),
            'member_contrib' => $this->memberProgress(),
        );

        return $data;
    }

    /**
     * Get dashboard task overview
     */
    private function overview()
    {
        try {
            $get_overview = $this->conn->prepare('SELECT
                COUNT(CASE WHEN author_id = ? THEN 1 ELSE null END) AS myTasks,
                COUNT(CASE WHEN assigned_to_id = ? THEN 1 ELSE null END) AS assinged_to_me
                FROM issues limit 1
            ');
            $get_overview->execute(array($this->user, $this->user));
        } catch (\PDOException $th) {
            throw $th;
        }

        try {
            $getTasks = $this->conn->prepare('SELECT
                COUNT(CASE WHEN status_id = 1 THEN 1 ELSE null END) AS opened,
                COUNT(CASE WHEN status_id = 2 THEN 1 ELSE null END) AS in_progress,
                COUNT(CASE WHEN status_id = 5 THEN 1 ELSE null END) AS closed
                FROM issues where author_id = ? or assigned_to_id = ? limit 1
            ');
            $getTasks->execute(array($this->user, $this->user));
        } catch (PDOException $th) {
            throw $th;
        }

        $overview = $get_overview->fetchObject() ?? [];
        $c3_overview = $getTasks->fetchObject() ?? [];


        $issues = $this->query("SELECT count(id) as issues from issues", []);

        $myTasks = $overview->myTasks ?? 0;
        $tasks_assinged_to_me = $overview->assinged_to_me ?? 0;

        $_c3_overview = array(
            'Abertas' => [(int)$c3_overview->opened ?? 0],
            'Em progresso' => [(int)$c3_overview->in_progress ?? 0],
            'Fechdas' => [(int)$c3_overview->closed ?? 0],
        );

        $_c3_overview_init = array(
            'Minhas Tarefas' => [(int) $myTasks ?? 0],
            'Atribuidas a min' => [(int) $tasks_assinged_to_me ?? 0],
            'Por Terminar' => [(int) sizeOf($this->hotTasks()) ?? 0],
        );

        $data = [];
        $data = array(
            'hotTasks' => sizeOf($this->hotTasks()),
            'my_tasks' => $myTasks,
            'tasks_assinged_to_me' => $tasks_assinged_to_me,
            'mc_task' => \number_format((($myTasks + $tasks_assinged_to_me) / $issues->fetchObject()->issues), 2),
            '_issues' => $c3_overview,
            '_c3_overview_init' => $_c3_overview_init,
            '_c3_overview' => $_c3_overview,
        );

        return $data;
    }

    /**
     * Display all issues that belongTo user
     *
     * @param int $user
     * @return \Query\Response\Issues
     */
    private function issues()
    {
        $issues = $this->query("SELECT
            issues.id, author_id, projects.name as project, project_id, tracker_id,
            trackers.name as tracker, issues.subject,
            issues.description, issues.priority_id,
            issues.due_date,
            issues.created_on, issues.updated_on
            from issues
                inner join projects on (projects.id = issues.project_id)
                inner join trackers on (trackers.id = issues.tracker_id)
            where (author_id = ?) or assigned_to_id = ? order by updated_on desc, created_on desc limit 10",
            [$this->user, $this->user]);

        $_issue = [];
        while($issue = $issues->fetchObject()){
            $_issue[] = array(
                'id' => $issue->id,
                'author_id' => $issue->author_id,
                'tracker_id' => $issue->tracker_id,
                'tracker' => $issue->tracker,
                'project_id' => $issue->project_id,
                'project' => $issue->project,
                'subject' => $issue->subject,
                'description' => $issue->description,
                'priority_id' => $issue->priority_id,
                'due_date' => Carbon::parse($issue->due_date)->subDay(1)->diffForHumans(),
                'created_on' => Carbon::parse($issue->created_on)->diffForHumans(),
                '_created_on' => $issue->created_on,
                'updated_on' => Carbon::parse($issue->updated_on)->diffForHumans(),
                '_updated_on' => $issue->updated_on,
            );
        }
        return $_issue;
    }

    /**
     * Display all the user hotTasks
     *
     * @param int $this->id
     * @return \Query\Response\Issues as hotasks
     */
    public function hotTasks($confDay = -5)
    {
        $hotTasks = $this->query(
            "SELECT
            issues.id, author_id, projects.name as project, project_id, tracker_id,
            trackers.name as tracker, issues.subject,
            issues.description, issues.priority_id,
            issues.due_date, issues.done_ratio,
            issues.created_on, issues.updated_on
            from issues
                inner join projects on (projects.id = issues.project_id)
                inner join trackers on (trackers.id = issues.tracker_id)
                where (author_id = ? or assigned_to_id = ?)
                and datediff(now(), due_date) >= $confDay
                and datediff(now(), due_date) <= 7
                and due_date is not null
                order by due_date desc limit 10",
            [$this->user, $this->user]);

        $data = [];

        $date = Carbon::now()->locale('pt_BR');
        while($hotTask = $hotTasks->fetchObject()){
            $data[] = array(
                'id' => $hotTask->id,
                'author_id' => $hotTask->author_id,
                'tracker_id' => $hotTask->tracker_id,
                'tracker' => $hotTask->tracker,
                'project_id' => $hotTask->project_id,
                'project' => $hotTask->project,
                'subject' => $hotTask->subject,
                'description' => $hotTask->description,
                'priority_id' => $hotTask->priority_id,
                'done_ratio' => $hotTask->done_ratio,
                'due_date' => Carbon::parse($hotTask->due_date)->diffForHumans(),
                'created_on' => Carbon::parse($hotTask->created_on)->diffForHumans(),
                '_created_on' => $hotTask->created_on,


                '__time' => Carbon::parse('2018-06-15 12:34:00', 'UTC'),
                'updated_on' => $hotTask->updated_on,
                '_due_date' => $hotTask->due_date,
            );
        }
        return $data;
    }


    /**
     * Get Programas por provincia
     */
    public function prov_programas($provincia = 'Maputo-Cidade')
    {
        $provProgramas = array();

        // Get project
        $projects = $this->query("SELECT * from projects where parent_id = ? order by name asc", [$this->defaultProject]);

        /**
         * loop thr the response and get all the projects that belongs to @provincia
         */
        $_project_child = [];
        $__project_child = [];
        while($project = $projects->fetchObject()){

            $provProgramas[] = array(
                'id' => $project->id,
                'project_name' => $project->name,
            );

            $project_childs = $this->query("SELECT projecto.id as id, projecto.name as projecto, projecto.parent_id from custom_values
                INNER JOIN projects as projecto ON projecto.id = customized_id
                WHERE value = ? and projecto.parent_id = ? ", [$provincia, $project->id]);

            while($project_child = $project_childs->fetchObject()){
                $_project_child[$project->name][] = array(
                    'child_project_id' => $project_child->id,
                    'child_project' => $project_child->projecto,
                );

                // $_project_child[$project->name][] = '--- '.$project_child->projecto;


            }

            // foreach ($_project_child[$project->name] as $key => $value) {
            //     // return $_project_child[$project->name];
            //     $__project_child[$project->name]['projects'] = \implode('</br>', $_project_child[$project->name]);
            //     $__project_child[$project->name]['num_projects'] = sizeOf($_project_child[$project->name]);
            // }
        }

        return $_project_child;
    }


    /**
     * Ge all recente activities
     */
    public function recent_activities()
    {
        $start_date = date('Y-m-d', strtotime('- 30 day'));

        $activities = $this->query("SELECT issues.id, issues.subject, issues.status_id as i_status, issue_statuses.name as status_name, projects.name as project, trackers.name as tracker, users.firstname, users.lastname, issues.created_on, issues.updated_on
            FROM issues
                inner join projects on projects.id = project_id
                inner join trackers on trackers.id = issues.tracker_id
                inner join issue_statuses on issue_statuses.id = status_id
                inner join users on users.id = author_id
            where issues.created_on >= $start_date and is_private = 0 order by updated_on desc limit 10", []);

        $_activities = [];
        while ($activitie = $activities->fetchObject()) {
            $_created_date = ucwords(\Carbon\Carbon::parse($activitie->updated_on)->formatLocalized('%d %B %Y'));
            if ($activitie->created_on == $activitie->updated_on) {
                $activitie->isUpdated = false;
                $activitie->_time = \Carbon\Carbon::parse($activitie->created_on)->diffForHumans();
            } else {
                $activitie->isUpdated = true;
                $activitie->_time = \Carbon\Carbon::parse($activitie->updated_on)->diffForHumans();
            }

            $_activities[$_created_date][] = $activitie;
        }
        return $_activities;
    }


    public function dataProvincias()
    {
        $provincias = $this->query("SELECT * from custom_fields where name = ? and type = ? and field_format = ?", ['Província (s)', 'ProjectCustomField', 'list']);

        $_provincias = Yaml::parse($provincias->fetchObject()->possible_values) ?? [];

        $data = array();
        foreach($_provincias as $provincia){
            $num = 0;
            foreach ($this->prov_programas($provincia) as $key => $_pProjects) {
                $num += sizeOf($_pProjects);
            }

            if($provincia == "Cabo Delgado"){
                $provincia = "Cabo-Delgado";
            }
            $data[$provincia] = array(
                'projects' => $num
            );
        }
        return $data;
    }


    public function memberProgress()
    {
        $member = null;
        $data = [];
        $projects = [];
        foreach ($this->issues() as $key => $project) {
            $projects[$project['project_id']][] = $project['project'];
        }

        foreach($projects as $project_id => $project){
            $getMembers = $this->query("SELECT user_id, project_id, firstname, lastname from members inner join users on users.id = user_id where project_id = ? and user_id != ? ", [$project_id, $this->user]);

            // return $project_id;
            while($member = $getMembers->fetchObject()){

                $member_contrib = $this->query("SELECT assigned_to_id,
                        count(case when done_ratio = 100 then 1 else null end) as concluido,
                        count(case when done_ratio < 100 then 1 else null end) as nao_concluido,
                        count(*) as total
                    FROM
                        issues
                    WHERE
                        project_id = ? AND (assigned_to_id = ? or author_id = ?)",
                        [
                            $member->project_id,
                            $member->user_id,
                            $member->user_id,
                        ]);

                $_member_contrib = $member_contrib->fetchObject() ?? [];
                if($_member_contrib->total == 0){

                    $data[] = array(
                        'user_id' => $member->user_id,
                        'firstname' => $member->firstname,
                        'lastname' => $member->lastname,
                        'project' => $project[0],
                        'tasks' => [
                            'done' => $_member_contrib->concluido,
                            'in_progress' => $_member_contrib->nao_concluido,
                            'total' => $_member_contrib->total,
                        ],
                    );

                }else{
                    $data[] = array(
                        'user_id' => $member->user_id,
                        'firstname' => $member->firstname,
                        'lastname' => $member->lastname,
                        'project' => $project[0],
                        'tasks' => [
                            'done' => $_member_contrib->concluido,
                            'in_progress' => $_member_contrib->nao_concluido,
                            'total' => $_member_contrib->total,
                        ],
                    );
                }
            }
        }

        return $data;
    }

    /**
     * Query Builder
     * @return Response
     */
    protected function query($query, array $parameters)
    {
        try {
            $query = $this->conn->prepare($query);
            $query->execute($parameters);
            return $query;
        } catch (\PDOException $e) {
            return $e->getMessage();
        }
    }
}
