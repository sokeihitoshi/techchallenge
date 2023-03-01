<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\WebCrawlerRequest;

class WebCrawlerController extends Controller
{

    public function start(Request $request)
    {
        return view(('crawl.form'));
    }

   /**
    * This is the method that will simply list all the files uploaded by name and provide a
    * link to each one so they may be downloaded
    *
    * @param $request : A standard form request object
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    * @throws BindingResolutionException
    */
    public function crawl(WebCrawlerRequest $request)
    {
        $urls = $request->url;
        $wordCount = 0;
        $titleLength = 0;
        $loadTime = 0;
        $pages = 0;
        $results = array();
        $images = array();
        $internalLinks = array();
        $externalLinks = array();
        $titleCount = 0;

        foreach($urls as $url) {
            error_log("URL: " . $url);
            $pages++;
            $timeStart = microtime(true);
            $combinedUrl = 'https://agencyanalytics.com/' . $url;
            
            $response = Http::get($combinedUrl);

            $results[] = array(
                'url' => $combinedUrl,
                'code' => $response->getStatusCode()
            );
            if ($response->successful() === true) {
                $rawHTML = (string) $response->getBody();
                $dom = new \DOMDocument();
                @$dom->loadHTML($rawHTML);
                $xPath = new \DOMXPath($dom);
    
                $linkNodes = $xPath->query('//main//a');
                $imgNodes = $xPath->query('//main//img');
    
                $wordNodes = $xPath->query('//main//text()');
                foreach ($wordNodes as $node) {
                    $wordCount += count(explode(" ", $node->textContent));
                }

                $titles = $this->wordCountAvg($xPath, '//main//h1//text()');
                $titleLength += $titles['wordCount'];
                $titleCount += $titles['titleCount'];
                $titles = $this->wordCountAvg($xPath, '//main//h3//text()');
                $titleLength += $titles['wordCount'];
                $titleCount += $titles['titleCount'];
    
                foreach ($imgNodes as $imgNode) {
                    $imgSrc = $imgNode->attributes->getNamedItem('src');
                    if ($imgSrc !== null) {
                        $img = $imgSrc->value;
                        if (in_array($img, $images) === false) {
                            error_log($img);
                            $images[] = $img;
                        }
                    }
                }
    
                foreach ($linkNodes as $linkNode) {
                    $linkHref = $linkNode->attributes->getNamedItem('href');
                    if ($linkHref !== null) {
                        $link = $linkHref->value;
                        if (strpos($link, '#') === 0) {
                            if (in_array($link, $internalLinks) === false) {
                                error_log($link);
                                $internalLinks[] = $link;
                            }
                        } else {
                            if (in_array($link, $externalLinks) === false) {
                                error_log($link);
                                $externalLinks[] = $link;
                            }
                        }
                    }
                }
    
    
                $loadTime += microtime(true) - $timeStart;
            }
        };
 
        return view('crawl.results', 
            array(
                'titleLengthAvg' => $titleLength/$titleCount,
                'wordCountAvg' => $wordCount/$pages,
                'imgCount' => count($images),
                'iLinks' => count($internalLinks),
                'xLinks' => count($externalLinks),
                'loadTime' => $loadTime/$pages,
                'pages' => $pages,
                'results' => $results
            ));
    }

    private function wordCountAvg(\DOMXPath $xPath, string $query) {
        $wordCount = 0;
        $nodes = $xPath->query($query);
        foreach ($nodes as $node) {
            $wordCount += count(explode(" ", $node->textContent));
        }
        
        return array(
            'wordCount' => $wordCount,
            'titleCount' => count($nodes)
        );
    }
}
