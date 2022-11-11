{* Smarty *}
{extends file="layouts/base.tpl"}
{block name="content"}
    <h1 class="page-title">Welcome</h1>
    <p class="lead">Browse categories and recent posts below.</p>

    {if $categoriesWithRecent|@count == 0}
        <p class="empty-state">No categories with articles yet. <a href="index.php?action=seed">Run the seeder</a> to add sample data.</p>
    {else}
        {foreach $categoriesWithRecent as $item}
            <section class="category-block">
                <h2 class="category-title">{$item.category.title}</h2>
                {if $item.category.description}
                    <p class="category-desc">{$item.category.description}</p>
                {/if}
                <ul class="post-list">
                    {foreach $item.recent_posts as $post}
                        <li class="post-item">
                            <a href="index.php?page=article&id={$post.id}" class="post-link">
                                {if $post.image}
                                    <img src="{$post.image}" alt="" class="post-thumb" loading="lazy">
                                {/if}
                                <span class="post-title">{$post.title}</span>
                                <span class="post-meta">{$post.published_at|date_format:"%b %d, %Y"} · {$post.views} views</span>
                            </a>
                        </li>
                    {/foreach}
                </ul>
                <a href="index.php?page=category&id={$item.category.id}" class="btn btn-outline">All Articles</a>
            </section>
        {/foreach}
    {/if}
{/block}
