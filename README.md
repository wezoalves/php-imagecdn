# CDN - Image Resize

real-time image manipulation and caching

#### Installation

Downloading the Project

- ```$ git clone git@github.com:ynvolve/cdn.git {path_cdn};```
- ```$ cd {path_cdn};```
- ```$ composer install; ```
- ```$ chmod -R 775 src/image; ```

Server Configuration

- Point the domain to the **src/** folder
- Images will be generated and stored in the **src/image** folder

#### Usage

```bash
https://{DOMAIN}/?
key={STRING_KEY}&
w={WIDTH}&
h={HEIGHT}&
u={URL_IMAGE}


# eg: https://domain-host-cdn.tld/?key=STRING_KEY&w=300&h=300&url=https://placehold.co/400x400.png
```

#### Params

| Param     | Description             | Type     |
| --------- | ----------------------- | -------- |
| w         | width                   | Integer  |
| h         | height                  | Integer  |
| u         | valid url (image file)  | String   |
| key       | key to domain           | String   |