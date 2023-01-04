<!DOCTYPE html>
<html lang="en-US">
<head>
<style type="text/css">
    *{ padding:0; margin:0; }
    html {  height: 100%;  }
    body{ background:#2e313a; height:100%; font-family: Tahoma!important; color: #90949f; font-size:13px; }

    .ci-toolbar-tabs{ float:left; width:200px; height:100%; position:fixed; top:0;left:0; }
    .ci-toolbar-tabs ul{list-style:none; background:#2e313a; border-right:1px solid #f03900; padding:24px 0; min-height: 100%;
        -moz-box-shadow: 0px 0px 13px 0px #000;
        -webkit-box-shadow: 0px 0px 13px 0px #000;
        -o-box-shadow: 0px 0px 13px 0px #000;
        box-shadow: 0px 0px 13px 0px #000;
        filter:progid:DXImageTransform.Microsoft.Shadow(color=#999, Direction=90, Strength=5);
    }
    .ci-toolbar-tabs li a{ color:#90949f; display:block; padding:14px 40px; text-decoration:none; border-right:4px solid transparent }
    .ci-toolbar-tabs li a.ajax{ background:url("<?= $profiler['ajax_requests']['icon'] ?>") no-repeat 10px center  }
    .ci-toolbar-tabs li a.database{ background:url("<?= isset($profiler['database']) ? $profiler['database']['icon'] : '' ?>") no-repeat 10px center  }
    .ci-toolbar-tabs li a.models{ background:url("<?= $profiler['models']['icon'] ?>") no-repeat 10px center  }
    .ci-toolbar-tabs li a.helpers{ background:url("<?= $profiler['helpers']['icon'] ?>") no-repeat 10px center  }
    .ci-toolbar-tabs li a.libraries{ background:url("<?= $profiler['libraries']['icon'] ?>") no-repeat 10px center  }
    .ci-toolbar-tabs li a.configuration{ background:url("<?= $profiler['config']['icon'] ?>") no-repeat 10px center  }
    .ci-toolbar-tabs li.active a, .ci-toolbar-tabs li a:hover{ background-position-x:18px; padding-left:50px; background-color: #343842; color:#FFF; border-left-color:#f03900; border-right-color:#f03900 }

    .ci-toolbar-tabs-detail{ float:left; width:60%; padding:20px 0px; position:relative; top:0; left:240px }
    .ci-toolbar-tabs-detail table{ border:0; padding:0; margin:0; width:100%; font-size:12px; }
    .ci-toolbar-tabs-detail table tr td{ padding:4px 8px; border-bottom:1px solid #343842 }
    .ci-toolbar-tabs-detail table tr td:first-child{ background:#343842; color:#f0f0f0 }
    .ci-toolbar-tabs-detail table thead td{ background: #343842 }
    .ci-toolbar-tabs-detail h1{ color:#f0f0f0; font-size:16px; text-transform:uppercase; padding:14px 0; }
    .ci-toolbar-tabs-detail div{ display: none }
    .ci-toolbar-tabs-detail div.display{ display: block }
    .ci-toolbar-tabs-detail p.list{ padding:6px 0; vertical-align:middle }
    .ci-toolbar-tabs-detail p.list:nth-child(even){ background: #343842 }
</style>
</head>
<body>
<?php if (empty($profiler)): ?>
    <p style="background:#f03900;color:#FFF;text-align: center;padding: 20px"><?= lang('profiler_key_has_expired') ?></p>
<?php else: ?>
<div class="ci-toolbar-tabs">
    <ul>
        <li class="active"><a href="#" class="ajax"><?= lang('ajax_requests') ?></a></li>
        <?php if (isset($profiler['database'])) : ?>
            <li><a href="#" class="database"><?= lang('database') . ' <span id="count_db_queries"></span>' ?></a></li>
        <?php endif; ?>
        <li><a href="#" class="models"><?= lang('models') . ' (' . count($profiler['models']['models']) . ')' ?></a></li>
        <li><a href="#" class="helpers"><?= lang('helpers') . ' (' . count($profiler['helpers']['helpers']) . ')' ?></a></li>
        <li><a href="#" class="libraries"><?= lang('libraries') . ' (' . count($profiler['libraries']['loaded_libraries']) . ')' ?></a></li>
        <li><a href="#" class="configuration"><?= lang('config') ?></a></li>
    </ul>
</div>
<div class="ci-toolbar-tabs-detail">
    <div class="ajax display">
        <h1>Ajax requests</h1>
        <?php $ajax_requests = $profiler['ajax_requests'] ?>
        <table cellspacing="0">
            <tr>
                <td><?= lang('method') ?></td>
                <td><?= strtoupper($ajax_requests['method']) ?></td>
            </tr>
            <tr>
                <td><?= lang('controller') ?></td>
                <td><?= $ajax_requests['controller'] ?></td>
            </tr>
            <tr>
                <td><?= lang('action') ?></td>
                <td><?= $ajax_requests['action'] ?></td>
            </tr>
            <tr>
                <td style="vertical-align: top"><?= lang('params') ?></td>
                <td><pre><?php print_r($ajax_requests['parameters']) ?></pre></td>
            </tr>
        </table>
    </div>
    <div class="database">
        <h1><?= lang('database') ?></h1>
        <table>
            <thead>
            <tr>
                <td><?= lang('server') ?></td>
                <td><?= lang('database') ?></td>
                <td><?= lang('queries') ?></td>
                <td style="text-align:right"><?= lang('time') . ' (' . lang('sec') . ')' ?></td>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($profiler['database'])): ?>
            <?php $dbs = $profiler['database']['dbs']; ?>
            <?php if (count($dbs)): ?>
                <?php
                $global_execution_time = 0;
                $count_queries         = 0;

                foreach ($dbs as $name => $db):?>
                    <tr>
                    <?php if (count($db['queries'])): ?>
                        <?php
                        $total_execution_time = 0;

                        foreach ($db['queries'] as $key => $query) {
                            $time      = number_format($db['query_times'][$key], 4);
                            $highlight = ['SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR&nbsp;', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')'];

                            foreach ($highlight as $bold) {
                                $query = str_replace($bold, '<strong style="color:#e0e0e0">' . $bold . '</strong>', $query);
                            }
                            echo '
                            <td>' . $db['hostname'] . '</td>
                            <td>' . $db['database'] . '</td>
                            <td>' . $query . '</td>
                            <td style="text-align:right">' . $time . '</td>';
                            $total_execution_time = array_sum($db['query_times']);
                            $global_execution_time += $total_execution_time;
                            $count_queries++;
                        }
                        ?>
                    <?php else: ?>
                        <td><?= $db['hostname'] ?></td>
                        <td><?= $db['database'] ?></td>
                        <td><?= lang('no_queries') ?></td>
                        <td style="text-align:right"></td>
                    <?php endif ?>
                    </tr>
                <?php endforeach ?>
                <span style="display: none;" id="count_queries"><?= $count_queries ?></span>
            <?php endif; ?>
            </tbody>
            <?php if ($global_execution_time > 0): ?>
                <tfoot>
                <tr style="background:#f03900;color:#FFF; text-align:right ">
                    <td colspan="3"><?= lang('total_execution_time') ?></td>
                    <td>
                        <?= '~' . number_format($global_execution_time, 4) ?>
                    </td>
                </tr>
                </tfoot>
            <?php endif ?>
        <?php endif; ?>
        </table>
    </div>
    <div class="helpers">
        <h1><?= lang('helpers') ?></h1>
        <?php
        $helpers = $profiler['helpers'];

    foreach ($helpers['helpers'] as $helper) {
        echo '
            <p class="list">' . ucfirst($helper) . '</p>';
    }
    ?>
    </div>
    <div class="models">
        <h1><?= lang('models') ?></h1>
        <?php
    $models = $profiler['models'];

    foreach ($models['models'] as $model) {
        echo '
            <p class="list">' . $model . '</p>';
    }
    ?>
    </div>
    <div class="libraries">
        <h1><?= lang('libraries') ?></h1>
        <?php
    $libraries = $profiler['libraries'];

    foreach ($libraries['loaded_libraries'] as $library) {
        echo '
            <p class="list">' . $library . '</p>';
    }
    ?>
    </div>
    <div class="configuration">
        <h1><?= lang('config') ?></h1>
        <table cellspacing="0">
            <thead>
            <tr>
                <td><?= lang('key') ?></td>
                <td><?= lang('value') ?></td>
            </tr>
            </thead>

            <?php
        $configuration = $profiler['config'];

    foreach ($configuration['configuration'] as $config => $val) {
        if (is_array($val) || is_object($val)) {
            $val = print_r($val, true);
        }
        echo '<tr>';
        echo '<td>' . $config . '</td>';
        echo '<td>' . htmlentities($val) . '</td>';
        echo '</tr>';
    }
    ?>
        </table>
    </div>
</div>
    <script type="text/javascript">
        var hasClass = function (el, cssClass) {
            return el.className.match(new RegExp('\\b' + cssClass + '\\b'));
        };
        var removeClass = function (el, cssClass) {
            el.className = el.className.replace(new RegExp('\\b' + cssClass + '\\b'), ' ');
        };
        var addClass = function (el, cssClass) {
            if (!hasClass(el, cssClass)) {
                el.className += " " + cssClass;
            }
        };
        var tabs = document.querySelector('div.ci-toolbar-tabs');
        var matches = tabs.querySelectorAll('a');
        var target = document.querySelector('div.ci-toolbar-tabs-detail');

        for(var i = 0; i< matches.length; i++){
            matches[i].addEventListener("click", function( event ) {
                var div = target.querySelectorAll('div.display');
                removeClass(div[0], 'display');
                var ele = target.querySelector('div.' + this.className);
                addClass(ele, 'display');

                tabs.querySelector('li.active').className = '';
                this.parentNode.className = 'active';
            }, false);
        }
        var dbQueries = document.getElementById('count_queries').textContent;
        document.getElementById('count_db_queries').textContent = ' ('+ dbQueries + ')';
    </script>
<?php endif; ?>
</body>
</html>
