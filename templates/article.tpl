{* Smarty *}
{extends file="layouts/base.tpl"}
{block name="content"}
    <article class="article-full">
        {if $article.image}
            <div class="article-hero-wrap">
                <img src="{$article.image}" alt="" class="article-hero" loading="lazy">
            </div>
        {/if}
        <div class="article-body-wrap">
            <h1 class="article-title">{$article.title}</h1>
            <div class="article-meta">
                {foreach $article.categories as $cat}
                    <a href="index.php?page=category&id={$cat.id}" class="category-tag">{$cat.title}</a>
                {/foreach}
                <span>{$article.views} views</span>
                {if $article.published_at}
                    <time datetime="{$article.published_at}">{$article.published_at|date_format:"%B %d, %Y"}</time>
                {/if}
            </div>
            {if $article.description}
                <p class="article-lead">{$article.description}</p>
            {/if}
            <div class="article-body">
                {$article.body_html|raw}
            </div>
        </div>
    </article>

    {if $similarArticles|@count > 0}
        <aside class="similar-articles">
            <h2 class="similar-articles-title">Similar Articles</h2>
            <ul class="similar-list">
                {foreach $similarArticles as $sim}
                    <li>
                        <a href="index.php?page=article&id={$sim.id}" class="article-card">
                            <div class="article-card-image-wrap">
                                {if $sim.image}
                                    <img src="{$sim.image}" alt="" class="article-card-image" loading="lazy">
                                {/if}
                            </div>
                            <div class="article-card-body">
                                <h3 class="article-card-title">{$sim.title}</h3>
                                {if $sim.published_at}
                                    <span class="article-card-date">{$sim.published_at|date_format:"%B %d, %Y"}</span>
                                {/if}
                                <span class="article-card-link">Continue Reading</span>
                            </div>
                        </a>
                    </li>
                {/foreach}
            </ul>
        </aside>
    {/if}
{/block}
