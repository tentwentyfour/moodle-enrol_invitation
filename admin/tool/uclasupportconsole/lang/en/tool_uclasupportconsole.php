<?php
$string['pluginname'] = 'UCLA support console';

$string['notitledesc'] = '(no description)';

// Titles
$string['logs'] = 'Log tools';
$string['users'] = 'User tools';
$string['srdb'] = 'Registrar tools';
$string['modules'] = 'Module tools';

// System logs
$string['syslogs'] = 'View last 1000 lines of a log';
$string['syslogs_info'] = 'If a selection is disabled, then the corresponding log file was not found.';
$string['syslogs_select'] = 'Select a log file';
$string['syslogs_choose'] = 'Choose log...';
$string['log_apache_error'] = 'Apache error';
$string['log_apache_access'] = 'Apache access';
$string['log_apache_ssl_access'] = 'Apache SSL access';
$string['log_apache_ssl_error'] = 'Apache SSL error';
$string['log_apache_ssl_request'] = 'Apache SSL access';
$string['log_shibboleth_shibd'] = 'Shibboleth daemon';
$string['log_shibboleth_trans'] = 'Shibboleth transaction';
$string['log_moodle_cron'] = 'Moodle cron';
$string['log_course_creator'] = 'Course creator';
$string['log_prepop'] = 'Pre-pop';

// Other logs
$string['prepopfiles'] = 'Show pre-pop files';
$string['prepopview'] = 'Show latest pre-pop output';
$string['prepoprun'] = 'Run prepop for one course';
$string['moodlelog'] = 'Show last 100 log entries';
$string['moodlelog_select'] = 'Select which types of log entries to view';
$string['moodlelog_filter'] = 'Filter log by action types';
$string['moodlelogins'] = 'Show logins during the last 24 hours';
$string['moodlelogbyday'] = 'Count Moodle logs by day';
$string['moodlelogbydaycourse'] = 'Count Moodle logs by day and course (past 7 days)';
$string['moodlelogbydaycourseuser'] = 'Count Moodle logs by day, course and user (past 7 days)';

// Users
$string['moodleusernamesearch'] = 'Show users with firstname and/or lastname';
$string['roleassignments'] = 'Count of role assignments on system';
$string['countnewusers'] = 'Show most recently created users';

// The SRDB
$string['enrollview'] = 'Get courses for view enrollment (<a target="_blank" href="https://ccle.ucla.edu/mod/page/view.php?id=3318">enrol2</a>)';

// For each stored procedure, the name is dynamically generated.
// The item itself will be there when the SP-object is coded, but there
// will be no explanation unless the code here is changed (or the SRDB
// layer is altered to include descriptions within the object).
$string['ccle_coursegetall'] = 'Get all courses in a subject area for BrowseBy (CCLE <a target="_blank" href="https://ccle.ucla.edu/mod/page/view.php?id=3305">ccle_coursegetall</a>)';
$string['ccle_courseinstructorsget'] = 'Get instructors for course (<a target="_blank" href="https://ccle.ucla.edu/mod/page/view.php?id=3306">ccle_courseinstructorsget</a>)';
$string['ccle_getclasses'] = 'Get information about course (<a target="_blank" href="https://ccle.ucla.edu/mod/page/view.php?id=3308">ccle_getclasses</a>)';
$string['ccle_getinstrinfo'] = 'Get all instructors in a subject area (<a target="_blank" href="https://ccle.ucla.edu/mod/page/view.php?id=3309">ccle_getinstrinfo</a>)';
$string['ccle_roster_class'] = 'Get student roster for class (<a target="_blank" href="https://ccle.ucla.edu/mod/page/view.php?id=3310">ccle_roster_class</a>)';
$string['cis_coursegetall'] = 'Get all courses in a subject area  (CIS <a target="_blank" href="https://ccle.ucla.edu/mod/page/view.php?id=3311">cis_coursegetall</a>)';
$string['cis_subjectareagetall'] = 'Get all subject area codes and full names (<a target="_blank" href="https://ccle.ucla.edu/mod/page/view.php?id=3313">cis_subjectareagetall</a>)';
$string['ucla_getterms'] = 'Get terms information (<a target="_blank" href="https://ccle.ucla.edu/mod/page/view.php?id=3315">ucla_getterms</a>)';
$string['ucla_get_user_classes'] = 'Get courses for My sites (<a target="_blank" href="https://ccle.ucla.edu/mod/page/view.php?id=16788">ucla_get_user_classes</a>)';

$string['courseregistrardifferences'] = 'Show courses with changed descriptions';

// Module
$string['nosyllabuscourses'] = 'Show courses with no syllabus';
$string['assignmentquizzesduesoon'] = 'Show courses with assignments or quizzes due soon';
$string['modulespercourse'] = 'Count module totals and module types per course';

// Course
$string['collablist'] = 'Show collaboration sites';

// Capability string
$string['tool/uclasupportconsole:view'] = 'Access UCLA support console';

// Form input strings
$string['choose_term'] = 'Choose term...';
$string['term'] = 'Term';
$string['srs'] = 'SRS';
$string['subject_area'] = 'Subject area';
$string['choose_subject_area'] = 'Choose subject area...';
$string['uid'] = 'UID';

$string['srslookup'] = "SRS number lookup (Registrar)";

// capability strings
$string['uclasupportconsole:view'] = 'Use UCLA support console';