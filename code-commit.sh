echo "Hi CJ..."
echo "This to your code commit shell script."
echo "You created me to simplify your life. See status below"
git status
echo "Which of these file(s) are you staging"
read -r files
echo "Nice, CJ. Let me stage $files"
git add "$files"
echo "Files staged. Please what is the commit message (please include quotations)?"
read -r commit
echo "Cool.. Let me get on that for you."
git commit -m "$commit"
echo "Okay I'm pushing your code now.. fingers crossed"
git push
git status
