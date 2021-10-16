<img src="<?php echo $icon ?>" title="<?php echo lang('database') ?>"
     alt="<?php echo lang('database') ?>"/> <?php echo(count($dbs) ? lang('database') : 'N/A') ?>
<?php if(count($dbs)): ?>
<div class="detail database">
    <div class="scroll">
    <?php
    $global_execution_time = 0;
    foreach ($dbs as $name => $db):
        if (count($db['queries'])) {
            echo 'Host:' . $db['hostname'] . '/' . $db['database'] . '<br/>';
            $total_execution_time = 0;
            echo '<div class="scrolls">';
            foreach ($db['queries'] as $key => $query) {
                $time = number_format($db['query_times'][$key], 4);
                $highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR&nbsp;', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');
                foreach ($highlight as $bold) {
                    $query = str_replace($bold, '<strong style="color:#e0e0e0">'.$bold.'</strong>', $query);
                }
                echo '
                <p>
                    <span class="left-col">' . $query . '</span>
                    <span class="right-col">' . $time . ' ' .lang('sec') .'</span>
                </p>';
            }
            $total_execution_time = array_sum($db['query_times']);
            $global_execution_time += $total_execution_time;
            echo '
            </div>
            <p style="border-top:1px solid #57595E;">
                <span class="left-col">' . lang('total_execution_time') . '</span>
                <span class="right-col">' . number_format($total_execution_time, 4) . ' ' .lang('sec') .'</span>
            </p>';
        }
        else{
			echo 'Host:' . $db['hostname'] . '/' . $db['database'] . ' #'. lang('no_queries') . '<br/>';
        }
        ?>
    <?php endforeach ?>

    <?php
    if ($global_execution_time > 0) {
        echo '
            <p style="border-top:1px solid #f03900;">
                <span class="left-col"></span>
                <span class="right-col" style="color: #f03900;">' . number_format($global_execution_time, 4) . ' ' .lang('sec') .'</span>
            </p>';
    }
    ?>
    </div>
</div>
<?php endif; ?>
