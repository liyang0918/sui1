#!/bin/bash
# Created By:      LiYang <Liyang@unknownspace.com>
# Created Time:    2015-08-31 11:39:15
# Modified Time:   2015-09-28 09:00:13

working=`pwd`
project='/usr/local/apache/htdocs/mobile/forum'

function save_to_local_git()
{
    if [ $# -eq 1 ]; then  
        path=./$1
        for filename in `ls $path`
        do
            if [ -d $filename ]; then
                save_to_local_git $path/$filename
            else
                git add $path/$filename
            fi
        done
    else
        for filename in `ls`
        do
            if [ -d $filename ]; then
                save_to_local_git $filename
            else
                git add $filename
            fi
        done
    fi
    git commit -m "`date`"
}

function sync()
{
    `cp *.php $working -rf`
    [ -d $working/js ]||`mkdir $working/js`
    `cp ./js/* $working/js/ -rf`

    [ -d $working/request ]||`mkdir $working/request`
    `cp ./request/* $working/request/ -rf`
            
    [ -d $working/css ]||`mkdir $working/css`
    `cp ./css/* $working/css/ -rf`
    cd $working
    save_to_local_git
}

function cleanup()
{
    for filename in `ls`
    do
        len=${#filename}
        if [ ${filename:$len-1:1} == "~" ]; then
            echo remove $filename
            rm -rf $filename
        fi
    done
}

function git_show()
{
    if [ $1 -eq 1 ]; then
        git log
    else
        git log --graph --pretty=oneline --abbrev-commit
    fi
}


function git_init()
{
    `git init`
}

if [ $# -eq 0 ]; then
    cd $project 
    sync
fi

for i in $*
do
    case "$i" in
        'sync'|'-sync'|'--sync' )
            cd $project 
            sync;;  
        'clean'|'-clean'|'--clean' )
            cd $working
            cleanup;;
        'init'|'-init'|'--init' )
            cd $working
            `git init`;;
        'show'|'-show'|'--show')
            git_show;;
        'log'|'-log'|'--log')
            git_show 1;;
    esac
done

