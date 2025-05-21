#!/usr/bin/bash

declare -r start='1'
declare -r new='2'
declare -r ngrokdomain='star-panda-literally.ngrok-free.app'

echo "=====Backend Runner to start the Market Price Tracker website====="
echo ""

echo "Choose what type of setup you want to run:"
echo "1 for the default setup"
echo "2 for the new setup"

read -r -p "Enter your choice: " choice
echo ""


if [ "$choice" -eq "$start" ]; then 

echo "Default setup will now begin" 
echo "--------------------------------------------"
echo "Xampp as the local server for app.php"
echo "VueJS as the frontend"
echo "Ngrok as the tunneling service for VueJS"
echo "Serveo as the backup tunneling service for app.php"
echo "--------------------------------------------"


start() {
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

    echo ""
    echo "Start Serveo"
    read -r -p "Enter 1 to select: " choice
    echo ""

    if [ "$choice" -eq "$start" ]; then

        start5(){
            tilix --command "bash -c 'ssh -R 80:localhost:8080 serveo.net; exec bash'" &
            sleep 2
        }   
        start5
    else
        echo "Aborted"
    fi
}
start

start6(){
        echo "--------------------------------------------"
        echo "Ngrok is tunneling on port: $portvue"
        echo "Ngrok is running on the domain: https://$ngrokdomain"
        echo "VueJS is running on port: $portvue"
        echo "Serveo is tunneling on port: 8080"
        echo "--------------------------------------------"
    }
start6

elif [ "$choice" -eq "$new" ]; then

    echo "New setup will now begin" 
    echo "--------------------------------------------"
    echo "Xampp as the local server for app.php"
    echo "VueJS as the frontend"
    echo "Ngrok as the tunneling service for Xampp to use app.php"
    echo "Serveo as the backup tunneling service for Xampp to use app.php"
    echo "--------------------------------------------"

    start7(){
        
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
    }
    start7

    echo ""
    echo "Start Ngrok"
    read -r -p "Enter 1 to select: " choice
    echo ""

    if [ "$choice" -eq "$start" ]; then

        echo "Start Ngrok using a custom domain"
        read -r -p "Enter 1 to select: " choice
        echo ""

        if [ "$choice" -eq "$start" ]; then

            echo ""
            echo "Enter custom domain (do not put the https or com)"
            read -r -p "Custom Domain: " ngrokctdomain
            echo ""

            start8(){
                tilix --command "bash -c 'ngrok http http://localhost:8080 --domain=$ngrokctdomain; exec bash'" &
                sleep 2
            }
            start8

        else

            echo "Proceeding with the default domain ($ngrokdomain)"

            start9(){
                tilix --command "bash -c 'ngrok http http://localhost:8080 --domain=$ngrokdomain; exec bash'" &
                sleep 2
            }
            start9
        fi
    else
        echo "Aborted"
    fi

    echo ""
    echo "Start Serveo"
    read -r -p "Enter 1 to select: " choice
    echo ""

    if [ "$choice" -eq "$start" ]; then

        start10(){
            tilix --command "bash -c 'ssh -R 80:localhost:8080 serveo.net; exec bash'" &
            sleep 2
        }   
        start10
    else
        echo "Aborted"
    fi

    start11(){
        echo "--------------------------------------------"
        echo "Ngrok is tunneling on port: 8080"
        echo "Ngrok is running on the domain: https://$ngrokdomain"
        echo "Serveo is tunneling on port: 8080"
        echo "--------------------------------------------"
    }
    start11

else
    echo "Invalid choice. Please run the script again."
    exit 1
fi





