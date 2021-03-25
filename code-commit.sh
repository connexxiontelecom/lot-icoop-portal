echo "Hi CJ..."
sleep 2s
echo "This to your code commit shell script."
sleep 2s
echo "You created me to simplify your life"
sleep 2s
echo "Which repo are we committing today, CJ?"
read -r repo
sleep 2s
echo "Cool. Which file(s) are you staging in $repo?"
read -r files
echo "Nice, CJ. Let me stage $files"
sleep 2s
git add "$files"
echo "Files staged. Please what is the commit message?"
read -r commit
echo "Cool.. Let me get on that for you."
git commit -m "$commit"
sleep 2s
echo "Okay I'm pushing your code now.. fingers crossed"
git push
sleep 2s
echo "All done.. don't forget to push to dev for conflict resolution and then pull again to fast-forward your branch"
sleep 2s
echo "Perhaps you can automate PRs as well from here. Enjoy, CJ"
