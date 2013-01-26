<?php
/**
* Cravs ID Api class
*/
class CravsID
{
  protected
      $app_id   = $config['app_id'],
      $app_key  = $config['app_key'],
      $url      = 'id.cravs.com',
      $response = null,
      $useSSL   = false;

  public function auth($login, $password)
  {
    $params = array_merge(array(
      'username'      => $login,
      'password'      => $password,
      'grant_type'    => 'password',
      'client_id'     => $this->app_id,
      'client_secret' => $this->app_key
    ));
    return $this->httpPostRequest('oauth/token', $params);
    return $this->getIfSuccess('access_token');
  }

  public function getUserInfo($token)
  {
    return $this->httpGetRequest('api/v1/me?access_token='.$token);
     //$this->getIfSuccess();
  }

  protected function httpPostRequest($method, array $postdata)
  {
    $url = ($this->useSSL ? 'https://' : 'http://') . $this->url .'/'. $method;
    $post = http_build_query($postdata, '', '&');
    $ch = curl_init($url);
    if ($this->useSSL) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
  }

  protected function httpGetRequest($method)
  {
    $url = ($this->useSSL ? 'https://' : 'http://') . $this->url .'/'. $method;
    $ch = curl_init($url);
    if ($this->useSSL) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
  }
}
?>