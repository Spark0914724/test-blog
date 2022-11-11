{* Smarty *}
{extends file="layouts/base.tpl"}
{block name="content"}
    <article class="article-full">
        {if $article.image}
            <img src="{$article.image}" alt="" class="article-hero" loading="lazy">
        {/if}
        <h1 class="article-title">{$article.title}</h1>
        <div class="article-meta">
            {foreach $article.categories as $cat}
                <a href="index.php?page=category&id={$cat.id}" class="category-tag">{$cat.title}</a>
            {/foreach}
            <span class="views">{$article.views} views</span>
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
    </article>

    {if $similarArticles|@count > 0}
        <aside class="similar-articles">
            <h2>Similar Articles</h2>
            <ul class="similar-list">
                {foreach $similarArticles as $sim}
                    <li>
                        <a href="index.php?page=article&id={$sim.id}" class="similar-link">
                            {if $sim.image}
                                <img src="{$sim.image}" alt="" class="similar-thumb" loading="lazy">
                            {/if}
                            <span class="similar-title">{$sim.title}</span>
                        </a>
                    </li>
                {/foreach}
            </ul>
        </aside>
    {/if}
{/block}
