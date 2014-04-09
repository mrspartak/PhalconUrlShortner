<?
$app->get(
	'/',
	function () use ($app) {
		echo $app['view']->render(
			'index/index',
			 array(
				'token'	=> $app->security->getSessionToken(),
				'url' 	=> $app->config->app->base_uri
			)
		);
	}
);

$app->get(
	'/{short:[a-zA-Z\-]+}',
	function ($short) use ($app) {
		$url = Urls::checkShortExistance($short);
		if($url !== false) {
			$app->response->redirect($url->long, true)->send();
		} else {
			$app->response->redirect("/")->send();
		}
	}
);

$app->post(
	'/ajax',
	function () use ($app) {
		if (!$app->request->isAjax()) {
			$app->response->setStatusCode(404, "Not Found")->sendHeaders();
			return false;
		}
		
		$action = $app->request->getPost('action');
		$data   = $app->request->getPost('data');
		$filter = new \Phalcon\Filter();

		switch ($action) {
			case 'get_short':
				$longUrl = $data['url'];
				
				$url = Urls::checkLongExistance($longUrl);
				if($url === false) {
					for($i=0, $tries = 10; $i < $tries; $i++) {
						$shortUrl = Urls::createShortUrl();
						
						$url = Urls::checkShortExistance($url);
						if($url === false) {
							$url = new Urls();
							$url->short = $shortUrl;
							$url->long = $longUrl;
							$url->save();
							$i = $tries;
						}
					}	
				}
				
				if($url->long == $longUrl)
					$response = $url;
				else
					$response = array(
						'error' => 'create_url'
					);
			break;
      
			default:
				$app->response->setStatusCode(404, "Not Found")->sendHeaders();
		}

		$app
			->response
			->setContentType('application/json', 'UTF-8')
			->setJsonContent($response, JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE)
			->send();
    }
);


$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(500, "Error")->sendHeaders();
        echo $app['view']->render('errors/404', array('message' => 'error_404'));
    }
);