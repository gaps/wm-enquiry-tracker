rsync -vizr --exclude=".*/" --exclude-from=rsync-exclude.txt  -e ssh /home/naveen/public_html/wm-enquiry.localhost.com/trunk/ guser@demo-tracker.wisdommart.in:~/domains/enquiry-tracker.wisdommart.in