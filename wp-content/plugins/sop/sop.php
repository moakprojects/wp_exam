<?php

/*
Plugin Name: SOP
Plugin URI: 
Description: This is a Søgemaskine Optimering Plugin.
Version: 1.0
Author: Erika Grebur, Akos Molnar
*/

if(!function_exists('add_action')) {
    echo "You cannot access directly to the plugin";
    exit;
}

session_start();

class Sop {
  

    function __construct() {

    }

    // create admin menu page
    function add_admin_menu() {
        add_menu_page(
            'Søgemaskine Optimering Plugin',
            'SOP',
            'manage_options',
            'sop',
            array($this,'sop'),
            'dashicons-feedback',
            35 );
    }

    // add a custom toolbar item into toolbar menu in the header
    function add_toolbar_item($wp_admin_bar) {

        $title = 'The quality of your page is counting...';
        
        $wp_admin_bar -> add_node(array(
            'id' => 'sop-bar',
            'title' => $title,
            'href' => admin_url('admin.php?page=sop')
        ));
    }

    // rewite the label of our custom toolbar item (if the page speed value is under 80 we indicate problem is occured) 
    function modify_toolbar_title($wp_admin_bar) {
       
        if(isset($_SESSION["toolbarTitle"])) {
        
            $wp_admin_bar->add_node(array(
                'id' => 'sop-bar',
                'title' => $_SESSION["toolbarTitle"]
            ));
        }

        return $wp_admin_bar;
    }

    // this method build-up the admin page
    function sop() {

        echo "<h3>SOP</h3> <span class='subTitle'>- your SEO friend</span>";

        echo '
            <div class="row">
                <div class="col s12">
                    <div class="start-screen">
                        <div class="loading">
                            <div class="loading__element el1">L</div>
                            <div class="loading__element el2">O</div>
                            <div class="loading__element el3">A</div>
                            <div class="loading__element el4">D</div>
                            <div class="loading__element el5">I</div>
                            <div class="loading__element el6">N</div>
                            <div class="loading__element el7">G</div>
                        </div>
                        <div class="loading">
                            <div class="loading__element el1"></div>
                            <div class="loading__element el2"></div>
                            <div class="loading__element el3">.</div>
                            <div class="loading__element el4">.</div>
                            <div class="loading__element el5">.</div>
                            <div class="loading__element el6"></div>
                            <div class="loading__element el7"></div>
                        </div>
                    </div>
                </div>
            </div>';

        echo '<div class="sopContainer hide">';

        if(isset($_POST["pageSpeed"])) {
            $screenshotData = $_POST["screenshot"];
            $screenshotSrc = str_replace(array('_', '-'), array('/', '+'), $screenshotData);
            $badgeStyle = $this->categorization($_POST['pageSpeed']);
            $this->titleToSession($_POST["pageSpeed"]);

            echo "";
        echo '
            <div class="row generalValues">
                <div class="col s4 offset-s1 center-align">
                    <div class="card-panel">
                        <img src="data:image/jpeg;base64, ' . $screenshotSrc . '" alt="screenshot">
                    </div>
                </div>
                <div class="col s2 offset-s1 speed">
                    <div class="card-panel gradeContainer">
                        <div class="row noMargin">
                            <div class="col s12">
                                <p class="noMargin title center-align">Page speed grade</p>
                            </div>
                        </div>
                        <div class="row noMargin">
                            <div class="col s4 offset-s2">
                                <div class="badgeGrade" style="background-color: ' . $badgeStyle["backgroundColor"] . '">
                                    <p class="center-align">' . $badgeStyle["title"] . '</p>
                                </div>
                            </div>
                            <div class="col s4">
                                <p class="score">' . $_POST['pageSpeed'] . '</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s2 offset-s1 speed">
                    <div class="card-panel gradeContainer">
                        <div class="row noMargin">
                            <div class="col s12">
                                <p class="noMargin title center-align">Load time</p>
                            </div>
                        </div>
                        <div class="row noMargin">
                            <div class="col s12">
                                <p class="score center-align">' . $_POST['elapsedTime'] . ' s</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }
        
        echo '
            <div class="row">
                <h4 class="col s11 offset-s1">Page speed insights</h4>
            </div>';
        echo '<div class="row">';
        echo '<ul class="collapsible col s10 offset-s1">';
        echo '
            <li>
                <div class="row noMargin">
                    <div class="col s2">
                        <p class="center-align">Grade</p>
                    </div>
                    <div class="col s10">
                        <p>Suggestion</p>
                    </div>
                </div>
            </li>
            ';
        if(isset($_POST["ruleResults"])) {
            foreach($_POST["ruleResults"] as $ruleResult) {
                $badgeStyle = $this->categorization($ruleResult[1]);
                if(isset($ruleResult[4])) {
                    $format = $this->summaryFormatCompletion($ruleResult[2], $ruleResult[3], $ruleResult[4]);
                } else {
                    $format = $ruleResult[2];
                }
                echo '
                <li>
                    <div class="collapsible-header row noMargin">
                        <div class="col s1">
                            <div class="subBadge" style="background-color: ' . $badgeStyle["backgroundColor"] . '">
                                <p class="center-align">' . $badgeStyle["title"] . '</p>
                            </div>
                        </div>
                        <div class="col s1">
                            <p>' . $ruleResult[1] . '</p>
                        </div>
                        <div class="col s9">
                            <p>' . $ruleResult[0] . '</p>
                        </div>
                        <div class="col s1 valign-wrapper">
                            <i class="material-icons">arrow_drop_down</i>
                        </div>
                    </div>
                    <div class="collapsible-body">
                        <p>' . $format . '</p>
                    </div>
                </li>
                ';
            }
        }
        echo '</ul>';
        echo '</div>';

        $metaTags = $this->sop_check_meta_tags();
        
        echo "
        <div class='row'>
            <div class='col s5 offset-s1'>
                <div class='row noMargin'>
                    <h4 class='col s11'>Meta tags check</h4>
                </div>'
                <div class='row'>
                    <div class='col s10 noLeftPadding'>
                        <table class='metaTable'>
                            <thead>
                            <tr>
                                <th>Meta tag</th>
                                <th>Exist</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Content-Type</td>
                                <td>" . (isset($metaTags["content-type"]) ? "<i class='material-icons goodTitle'>check</i>" : "<i class='material-icons lowTitle'>close</i>") . "</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>" . (isset($metaTags["description"]) ? "<i class='material-icons goodTitle'>check</i>" : "<i class='material-icons lowTitle'>close</i>") . "</td>
                            </tr>
                            <tr>
                                <td>Viewport</td>
                                <td>" . (isset($metaTags["viewport"]) ? "<i class='material-icons goodTitle'>check</i>" : "<i class='material-icons lowTitle'>close</i>") . "</td>
                            </tr>
                            <tr>
                                <td>Robots</td>
                                <td>" . (isset($metaTags["robots"]) ? "<i class='material-icons goodTitle'>check</i>" : "<i class='material-icons lowTitle'>close</i>") . "</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class='col s5 noRightPadding'>
                <div class='row'>
                    <h4 class='col s11'>Content breakdown by MIME type</h4>
                </div>
                <div class='row'>
                    <div class='col s12'>
                        <div id='pieChart'></div>
                        <div id='totalContentSize'></div>
                    </div>
                </div>
            </div>
        </div>";

        echo '
            <div class="tryAgainContainer hide center-align">
                <p>Oops! Something went wrong.</p>
                <p>Please check your connection and try it again.</p>
                <a class="btn waves-effect waves-light blue" onclick="location.reload()">Try again</a>
            </div>
            ';

        echo '</div>'; //sopContainer closing div
    }

    // our categorization system what is evaulate the given value and give back title az color
    function categorization($score) {
        
        $categoryStyle = array();
        
        if($score >= 80) {
            $categoryStyle["backgroundColor"] = '#00c853';
            
            if($score >= 90) {
                $categoryStyle["title"] = "A";
            } else {
                $categoryStyle["title"] = "B";
            }
        } else if($score >= 60) {
            $categoryStyle["backgroundColor"] = '#ffd600';

            if($score >= 70) {
                $categoryStyle["title"] = "C";
            } else {
                $categoryStyle["title"] = "D";
            }
        } else {
            $categoryStyle["backgroundColor"] = '#dd2c00';

            if($score >= 50) {
                $categoryStyle["title"] = "E";
            } else {
                $categoryStyle["title"] = "F";
            }
        }

        return $categoryStyle;
    }

    // we save current label of our toolbar item into session, so during two analyzes we can display the value from the session
    function titleToSession($score) {
        
        if($score >= 80) {
            $_SESSION["toolbarTitle"] = "<span class='ab-icon star'></span> <span class='goodTitle'>Your page quality is Good</span>";
            
        } else if($score >= 60) {
            $_SESSION["toolbarTitle"] = "<span class='ab-icon halfStar'></span> <span class='mediumTitle'>Your page quality is Medium</span>";

        } else {
            $_SESSION["toolbarTitle"] = "<span class='ab-icon emptyStar'></span> <span class='lowTitle'>Your page quality is Low</span>";

        }
    }

    // we select valueable information from API response
    function summaryFormatCompletion($format, $type, $args) {
        if($type === 'HYPERLINK') {
            $formatChange = str_replace('{{BEGIN_LINK}}', "<a href='" . $args[0][1] ."' target='_blank'>", $format);
            $summaryFormat = str_replace('{{END_LINK}}', '</a>', $formatChange);
        } else if($type === 'INT_LITERAL') {
            if(strpos($format, '{{NUM_SCRIPTS}}') && strpos($format, '{{NUM_CSS}}')) {
                $formatChange = str_replace('{{NUM_SCRIPTS}}', $args[0][1], $format);
                $summaryFormat = str_replace('{{NUM_CSS}}', $args[1][1], $formatChange);
            } else if(strpos($format, '{{NUM_REDIRECTS}}')) {
                $summaryFormat = str_replace('{{NUM_REDIRECTS}}', $args[0][1], $format);
            } else {
                $summaryFormat = $format;
            }
            
        } else {
            $summaryFormat = $format;
        }

        return $summaryFormat;
    }

    // we bind our javascript an css file and we also bind Materialize for the styling
    function enqueue() {
        wp_enqueue_style('materializeCss', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css');
        wp_enqueue_style('materializeIcons', 'https://fonts.googleapis.com/icon?family=Material+Icons');
        wp_enqueue_script('materializeJs', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js');
        wp_enqueue_script('highchartJs', 'https://code.highcharts.com/highcharts.js');
        wp_enqueue_script('myPluginScript', plugins_url('/admin/js/main.js', __FILE__));
        wp_enqueue_style('myPluginStyle', plugins_url('/admin/css/style.css', __FILE__));
        wp_localize_script( 'myPluginScript', 'sop_ajax',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
    }

    // this method will call the enqueue method thorugh action hook
    function register() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
    }

    // we create a dasboard widget what will appear at the top of the page
    function add_dashboard_widget() {

        wp_add_dashboard_widget(
                'sop_dashboard_widget',
                'SOP',
                array($this,'sop_dashboard_widget')
            );

            global $wp_meta_boxes;
            $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

            $sop_widget_backup = array( 'sop_dashboard_widget' => $normal_dashboard['sop_dashboard_widget'] );
            unset( $normal_dashboard['sop_dashboard_widget'] );
        
            $sorted_dashboard = array_merge( $sop_widget_backup, $normal_dashboard );
        
            $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
    }

    // we just want to display the most problematic impacts, here we filter out them
    function getProblematicImpacts($ruleResults) {
        $problematicImpacts = array();
        foreach($ruleResults as $impact) {
            if($impact[1] < 80) {
                array_push($problematicImpacts, array($impact[1], $impact[0]));
            }
        }

        usort($problematicImpacts, function($a, $b) {
            return $a[0] <=> $b[0];
        });

        return $problematicImpacts;
    }

    // this method build-up the widget with content
    function sop_dashboard_widget() {

        echo "<div class='widgetContainer'>";
        if(isset($_POST["pageSpeed"])) {

            $badgeStyle = $this->categorization($_POST['pageSpeed']);
            $problematicImpacts = $this->getProblematicImpacts($_POST["ruleResults"]);

            $_SESSION["widgetData"]["pageSpeed"] = $_POST['pageSpeed'];
            $_SESSION["widgetData"]["problematicImpacts"] = $problematicImpacts;

            echo "
                <div class='row widgetFirstRow'>
                    <div class='col s6'>
                        <span class='widgetTitle'>Page speed grade:</span> 
                    </div>
                    <div class='col s2'>
                        <div class='badgeGrade widgetBadgeGrade' style='background-color: " . $badgeStyle["backgroundColor"] . "'>
                            <p class='center-align noMargin'>" . $badgeStyle["title"] . "</p>
                        </div>
                    </div>
                    <div class='col s2'>
                        <p class='score widgetScore left-align noMargin'>" . $_POST['pageSpeed'] . "</p>
                    </div>
                </div>
                <hr>
                <div class='row widgetSecondRow'>
                    <div class='col s12'>
                        <p>The main problems are:</p>
                    </div>
                </div>
                ";
                foreach($problematicImpacts as $impact) {
                    $badgeStyle = $this->categorization($impact[0]);
                    echo "
                    <div class='row widgetThirdRow'>
                        <div class='col s2'>
                            <div class='badgeGrade problemGrade' style='background-color: " . $badgeStyle["backgroundColor"] . "'>
                                <p class='center-align noMargin'>" . $badgeStyle["title"] . "</p>
                            </div>
                        </div>
                        <div class='col s2'>
                            <p class='score problemScore left-align noMargin'>" . $impact[0] . "</p>
                        </div>
                        <div class='col s8'>
                            <p class='score left-align problemTitle noMargin'>" . $impact[1] . "</p>
                        </div>
                    </div>
                    ";
                }
        } else if(isset($_SESSION["widgetData"])) {

            $badgeStyle = $this->categorization($_SESSION["widgetData"]['pageSpeed']);

            echo "
            <div class='row widgetFirstRow'>
                <div class='col s6'>
                    <span class='widgetTitle'>Page speed grade:</span> 
                </div>
                <div class='col s2'>
                    <div class='badgeGrade widgetBadgeGrade' style='background-color: " . $badgeStyle["backgroundColor"] . "'>
                        <p class='center-align noMargin'>" . $badgeStyle["title"] . "</p>
                    </div>
                </div>
                <div class='col s2'>
                    <p class='score widgetScore left-align noMargin'>" . $_SESSION["widgetData"]['pageSpeed'] . "</p>
                </div>
            </div>
            <hr>
            <div class='row widgetSecondRow'>
                <div class='col s12'>
                    <p>The main problems are:</p>
                </div>
            </div>
            ";

            foreach($_SESSION["widgetData"]['problematicImpacts'] as $impact) {

                $badgeStyle = $this->categorization($impact[0]);

                echo "
                <div class='row widgetThirdRow'>
                    <div class='col s2'>
                        <div class='badgeGrade problemGrade' style='background-color: " . $badgeStyle["backgroundColor"] . "'>
                            <p class='center-align noMargin'>" . $badgeStyle["title"] . "</p>
                        </div>
                    </div>
                    <div class='col s2'>
                        <p class='score problemScore left-align noMargin'>" . $impact[0] . "</p>
                    </div>
                    <div class='col s8'>
                        <p class='score left-align problemTitle noMargin'>" . $impact[1] . "</p>
                    </div>
                </div>
                ";
            }
        } else {
            echo "<p>Your data is loading...</p>";
        }
        echo "</div>";
    }

    //check for meta tags
    function sop_check_meta_tags() {
        return get_meta_tags($_SERVER['HTTP_HOST']);
    }
}

// if the class exist then we instantiate its an call action an filter hooks
if (class_exists('Sop')) {
    $sopObj = new Sop();
    $sopObj->register();
    add_action('wp_ajax_sop', array($sopObj, 'sop'));
    add_action('wp_ajax_sop_widget', array($sopObj, 'sop_dashboard_widget'));
    add_action('wp_dashboard_setup', array($sopObj, 'add_dashboard_widget'));
    add_action('admin_bar_menu', array($sopObj,'add_toolbar_item'), 100);
    add_action('admin_menu', array($sopObj,'add_admin_menu'));
    add_filter('admin_bar_menu', array($sopObj, 'modify_toolbar_title'), 200);
}

?>