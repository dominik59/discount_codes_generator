#!/usr/bin/env bash
echo "Dodanie repozytoriow";
echo "deb http://ppa.launchpad.net/ansible/ansible/ubuntu trusty main" | sudo tee -a /etc/apt/sources.list
apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 93C4A3FD7BB9C367
apt-get update
echo "Instalacja ansibla";
apt-get install ansible
echo "Uruchomienie ansibla";
ansible-playbook --inventory="localhost," -c local init.yml
echo "Instalacja composera";
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
(cd .. ; composer install)