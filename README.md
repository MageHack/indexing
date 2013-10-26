###Magehack_Indexing

## 1. Overview
Move the indexers to a queue to make the back-end more responsive.


## 2. Installation, Configuration & Usage
**1. Install the Lilmuckers_Queue free Magento module that we use to create queues and workers:**
https://github.com/lilmuckers/magento-lilmuckers_queue

**2. Install phpBeanstalkd work queue**
http://kr.github.io/beanstalkd/
* you don’t need to configure it, just start it by calling beanstalkd in command line

**3. Add beanstalkd to your magento configuration:**
* open /etc/local.xml and add the following in the `<global>` node:
	
```
<queue>
	<backend>beanstalkd</backend>
		<beanstalkd>
			<servers>
				<server>
					<host>127.0.0.1</host>
				</server>
			</servers>
		</beanstalkd>
	</queue> 
```

**4. Install the Magehack_Indexing module and go to `System/Configuration/Catalog/Indexing` and enable Use Queue**
* now every time you do a reindex it will be added to the queue
* to run the queued items you need to run the /shell/queue.php:

`php -f queue.php --watch magehackindexing`

this will create a watcher and it will run all the indexing queues that we add

#### Author 
Alex Bejan <contact@bejanalex.com>

http://bejanalex.com