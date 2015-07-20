# cronerrors
* Limitations:
    commands are assumed to not have '#' or ';' or '2>' or '&1' in them
    2>&1 not supported - as these files will have info/debug messages as well.

* Expectation:
    Script will return error messages generated and saved in cron jobs' error files, along with file name in a string. This string should be mailed and worked on priority.

* Usage:
    Add to crontab and pipe result to mail

