#!/usr/bin/bash

declare -r start='1'
declare -r ngrokdomain='star-panda-literally.ngrok-free.app'

echo "=====Backend Runner to start the Market Price Tracker website====="
echo ""

echo "Start Xampp"
read -r -p "Enter 1 to start: " choice
echo ""

if [ "$choice" -eq "$start" ]; then

    start1() {
        cd /opt/lampp || exit
        sudo xhost +
        sudo ./xampp start
    }
    start1

else
    echo "Aborted"
fi

echo ""
echo "Start Vue"
read -r -p "Enter 1 to start: " choice
echo ""

if [ "$choice" -eq "$start" ]; then

    start2() {
        cd /home/alejandro/Documents/School\ Files/2yr-2nd-sem/App-Dev/Project/Main/market || exit
        #this starts vue
        tilix --command "bash -c 'npm run serve -- --https; exec bash'" &
        sleep 2
    }
    start2

else
    echo "Aborted"
fi

echo ""
echo "Start Ngrok"
read -r -p "Enter 1 to select: " choice
echo ""

if [ "$choice" -eq "$start" ]; then

    echo ""
    echo "Vue Port"
    read -r -p "Enter Vue port: " portvue
    echo ""

    echo "Start Ngrok using a custom domain"
    read -r -p "Enter 1 to select: " choice
    echo ""

    if [ "$choice" -eq "$start" ]; then

        echo ""
        echo "Enter custom domain (do not put the https or com)"
        read -r -p "Custom Domain: " ngrokctdomain
        echo ""

        start3(){
            tilix --command "bash -c 'ngrok http https://localhost:$portvue --domain=$ngrokctdomain; exec bash'" &
            sleep 2
        }
        start3

    else

        echo "Proceeding with the default domain ($ngrokdomain)"

        start4(){
            tilix --command "bash -c 'ngrok http https://localhost:$portvue --domain=$ngrokdomain; exec bash'" &
            sleep 2
        }
        start4
    fi
else
    echo "Aborted"
fi

start5(){
        echo "--------------------------------------------"
        echo "Ngrok is tunneling on port: $portvue"
        echo "Ngrok is running on the domain: https://$ngrokdomain"
        echo "VueJS is running on port: $portvue"
        echo "--------------------------------------------"
    }
start5

